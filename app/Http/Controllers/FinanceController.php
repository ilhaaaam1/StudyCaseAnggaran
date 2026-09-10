<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AlurPersetujuan;
use App\Models\PengajuanRab;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FinanceController extends Controller
{
    /**
     * Tampilkan dashboard Finance dengan metrik antrean dan persetujuan tahap 1.
     */
    public function index(Request $request): View
    {
        $totalAntreanPending = PengajuanRab::where('status', 'Pending')->count();
        $totalAccFinance = PengajuanRab::where('status', 'ACC Finance')->count();
        $totalDitolakFinance = PengajuanRab::where('status', 'Ditolak Finance')->count();
        $totalNominalPending = (float) PengajuanRab::where('status', 'Pending')->sum('estimasi_total');

        $antreanTerbaru = PengajuanRab::with(['pengguna', 'divisi'])
            ->where('status', 'Pending')
            ->latest('tanggal_pengajuan')
            ->take(5)
            ->get();

        return view('finance.dashboard', compact(
            'totalAntreanPending',
            'totalAccFinance',
            'totalDitolakFinance',
            'totalNominalPending',
            'antreanTerbaru'
        ));
    }

    /**
     * Tampilkan antrean pengajuan RAB yang membutuhkan review Finance (status: 'Pending').
     */
    public function antrean(Request $request): View
    {
        $search = $request->input('q');

        $query = PengajuanRab::with(['pengguna', 'divisi', 'rincianItem'])
            ->where('status', 'Pending');

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

        return view('finance.antrean_approval', compact('pengajuanList', 'search'));
    }

    /**
     * Tampilkan detail RAB untuk diverifikasi oleh Finance.
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

        return view('finance.show', compact('pengajuan'));
    }

    /**
     * Proses keputusan persetujuan Tahap 1 oleh Finance (ACC Finance / Ditolak Finance).
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
            'status_decision.required' => 'Keputusan persetujuan Finance wajib ditentukan.',
        ]);

        $reviewerId = (int) Auth::id();

        DB::transaction(function () use ($id, $reviewerId, $validated): void {
            $pengajuan = PengajuanRab::lockForUpdate()->findOrFail($id);

            // Validasi state: Finance hanya boleh memproses pengajuan status 'Pending'
            if ($pengajuan->status !== 'Pending') {
                abort(422, 'Pengajuan ini tidak dalam status Pending untuk diproses oleh Finance.');
            }

            $targetStatus = $validated['status_decision'] === 'ACC'
                ? 'ACC Finance'
                : 'Ditolak Finance';

            // 1. Update status di pengajuan_rab
            $pengajuan->update([
                'status' => $targetStatus,
            ]);

            // 2. Catat log persetujuan level 1 di alur_persetujuan
            AlurPersetujuan::create([
                'id_pengajuan' => $id,
                'id_reviewer' => $reviewerId,
                'level_persetujuan' => 1,
                'status_persetujuan' => $validated['status_decision'],
                'catatan' => $validated['catatan'] ?? ($validated['status_decision'] === 'ACC' ? 'ACC Tahap 1 oleh Finance' : 'Ditolak pada verifikasi Finance'),
                'tanggal_proses' => now(),
            ]);
        });

        $message = $validated['status_decision'] === 'ACC'
            ? 'Pengajuan RAB berhasil di-ACC Finance dan diteruskan ke Pimpinan untuk persetujuan akhir.'
            : 'Pengajuan RAB telah ditolak oleh Finance.';

        return redirect()->route('finance.antrean')
            ->with('success', $message);
    }

    /**
     * Tampilkan riwayat pengajuan yang telah diproses oleh Finance.
     */
    public function riwayat(Request $request): View
    {
        $statusFilter = $request->input('status');
        $search = $request->input('q');

        $query = PengajuanRab::with(['pengguna', 'divisi', 'alurPersetujuan.reviewer'])
            ->whereIn('status', ['ACC Finance', 'Ditolak Finance', 'ACC Final', 'Ditolak Pimpinan']);

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

        return view('finance.riwayat', compact('pengajuanList', 'statusFilter', 'search'));
    }
}
