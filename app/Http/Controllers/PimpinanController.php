<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\StatusPengajuan;
use App\Models\AlurPersetujuan;
use App\Models\PengajuanRab;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PimpinanController extends Controller
{
    /**
     * Tampilkan dashboard Pimpinan dengan ringkasan antrean persetujuan.
     */
    public function index(Request $request): View
    {
        $totalAntreanAccFinance = PengajuanRab::where('status', StatusPengajuan::MENUNGGU_PIMPINAN)->count();
        $totalAccFinal = PengajuanRab::whereIn('status', [StatusPengajuan::PROSES_PENCAIRAN, StatusPengajuan::SELESAI])->count();
        $totalDitolakPimpinan = PengajuanRab::where('status', StatusPengajuan::DITOLAK)->count();
        $totalAnggaranDisetujui = (float) PengajuanRab::whereIn('status', [StatusPengajuan::PROSES_PENCAIRAN, StatusPengajuan::SELESAI])->sum('estimasi_total');

        $antreanTerbaru = PengajuanRab::with(['pengguna', 'divisi', 'alurPersetujuan.reviewer'])
            ->where('status', StatusPengajuan::MENUNGGU_PIMPINAN)
            ->latest('tanggal_pengajuan')
            ->take(5)
            ->get();

        return view('pimpinan.dashboard', compact(
            'totalAntreanAccFinance',
            'totalAccFinal',
            'totalDitolakPimpinan',
            'totalAnggaranDisetujui',
            'antreanTerbaru'
        ));
    }

    /**
     * Tampilkan antrean pengajuan RAB yang membutuhkan persetujuan final Pimpinan.
     */
    public function antrean(Request $request): View
    {
        $search = $request->input('q');

        $query = PengajuanRab::with(['pengguna', 'divisi', 'rincianItem', 'alurPersetujuan.reviewer'])
            ->where('status', StatusPengajuan::MENUNGGU_PIMPINAN);

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('no_rab', 'like', "%{$search}%")
                    ->orWhere('judul_pengajuan', 'like', "%{$search}%")
                    ->orWhereHas('pengguna', function ($sub) use ($search): void {
                        $sub->where('nama_lengkap', 'like', "%{$search}%");
                    });
            });
        }

        $pengajuanList = $query->latest('tanggal_pengajuan')->paginate(10)->withQueryString();

        return view('pimpinan.antrean_approval', compact('pengajuanList', 'search'));
    }

    /**
     * Tampilkan detail RAB lengkap beserta catatan verifikasi Finance untuk direview Pimpinan.
     */
    public function show(int $id): View
    {
        $pengajuan = PengajuanRab::with([
            'pengguna.divisi',
            'divisi',
            'rincianItem',
            'dokumenPendukung',
            'alurPersetujuan.reviewer',
        ])->findOrFail($id);

        return view('pimpinan.show', compact('pengajuan'));
    }

    /**
     * Proses keputusan persetujuan Final (Tahap 2) oleh Pimpinan.
     */
    public function processApproval(Request $request, int $id): RedirectResponse
    {
        // Normalisasi input status
        $rawStatus = (string) ($request->input('status') ?? $request->input('status_persetujuan') ?? '');
        $statusDecision = 'ACC';
        if (strcasecmp($rawStatus, 'ditolak') === 0 || str_contains(strtolower($rawStatus), 'tolak')) {
            $statusDecision = 'Ditolak';
        }

        $request->merge(['status_decision' => $statusDecision]);

        $validated = $request->validate([
            'status_decision' => ['required', 'in:ACC,Ditolak'],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ], [
            'status_decision.required' => 'Keputusan persetujuan final Pimpinan wajib ditentukan.',
        ]);

        $pimpinanId = (int) Auth::id();

        DB::transaction(function () use ($id, $pimpinanId, $validated): void {
            $pengajuan = PengajuanRab::lockForUpdate()->findOrFail($id);

            // Validasi state
            if ($pengajuan->status !== StatusPengajuan::MENUNGGU_PIMPINAN) {
                abort(422, 'Pengajuan ini belum diverifikasi Finance atau sudah memiliki keputusan final.');
            }

            $targetStatus = $validated['status_decision'] === 'ACC'
                ? StatusPengajuan::PROSES_PENCAIRAN
                : StatusPengajuan::DITOLAK;

            // 1. Update status akhir di pengajuan_rab
            $pengajuan->update([
                'status' => $targetStatus,
            ]);

            // 2. Catat log persetujuan level 2 di alur_persetujuan
            AlurPersetujuan::create([
                'id_pengajuan' => $id,
                'id_reviewer' => $pimpinanId,
                'level_persetujuan' => 2,
                'status_persetujuan' => $validated['status_decision'],
                'catatan' => $validated['catatan'] ?? ($validated['status_decision'] === 'ACC' ? 'Persetujuan Final oleh Pimpinan' : 'Ditolak oleh Pimpinan pada tahap final'),
                'tanggal_proses' => now(),
            ]);
        });

        $message = $validated['status_decision'] === 'ACC'
            ? 'Pengajuan RAB berhasil disetujui dan diteruskan ke Finance untuk Proses Pencairan.'
            : 'Pengajuan RAB telah ditolak oleh Pimpinan.';

        return redirect()->route('pimpinan.antrean')
            ->with('success', $message);
    }

    /**
     * Tampilkan riwayat pengajuan dengan status final.
     */
    public function riwayat(Request $request): View
    {
        $statusFilter = $request->input('status');
        $search = $request->input('q');

        $query = PengajuanRab::with(['pengguna', 'divisi', 'alurPersetujuan.reviewer'])
            ->whereIn('status', [
                StatusPengajuan::PROSES_PENCAIRAN,
                StatusPengajuan::SELESAI,
                StatusPengajuan::DITOLAK,
            ]);

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('no_rab', 'like', "%{$search}%")
                    ->orWhere('judul_pengajuan', 'like', "%{$search}%");
            });
        }

        $pengajuanList = $query->latest('updated_at')->paginate(10)->withQueryString();

        return view('pimpinan.riwayat', compact('pengajuanList', 'statusFilter', 'search'));
    }
}
