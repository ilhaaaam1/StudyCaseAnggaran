<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\StatusPengajuan;
use App\Models\ActivityLog;
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
        $totalAntreanPending = PengajuanRab::where('status', StatusPengajuan::MENUNGGU_FINANCE)->count();
        $totalAccFinance = PengajuanRab::where('status', StatusPengajuan::MENUNGGU_PIMPINAN)->count();
        $totalDitolakFinance = PengajuanRab::where('status', StatusPengajuan::REVISI)->count();
        $totalNominalPending = (float) PengajuanRab::where('status', StatusPengajuan::MENUNGGU_FINANCE)->sum('estimasi_total');

        $antreanTerbaru = PengajuanRab::with(['pengguna', 'divisi'])
            ->where('status', StatusPengajuan::MENUNGGU_FINANCE)
            ->latest('tanggal_pengajuan')
            ->take(5)
            ->get();

        $antreanPencairanTerbaru = PengajuanRab::with(['pengguna', 'divisi'])
            ->where('status', StatusPengajuan::PROSES_PENCAIRAN)
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('finance.dashboard', compact(
            'totalAntreanPending',
            'totalAccFinance',
            'totalDitolakFinance',
            'totalNominalPending',
            'antreanTerbaru',
            'antreanPencairanTerbaru'
        ));
    }

    /**
     * Tampilkan antrean pengajuan RAB yang membutuhkan review Finance (status: 'Menunggu Verifikasi Finance').
     */
    public function antrean(Request $request): View
    {
        $search = $request->input('q');

        $query = PengajuanRab::with(['pengguna', 'divisi', 'rincianItem'])
            ->where('status', StatusPengajuan::MENUNGGU_FINANCE);

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
     * Tampilkan antrean pengajuan RAB yang siap dicairkan (status: 'Proses Pencairan').
     */
    public function antreanPencairan(Request $request): View
    {
        $search = $request->input('q');

        $query = PengajuanRab::with(['pengguna', 'divisi', 'rincianItem'])
            ->where('status', StatusPengajuan::PROSES_PENCAIRAN);

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('no_rab', 'like', "%{$search}%")
                    ->orWhere('judul_pengajuan', 'like', "%{$search}%")
                    ->orWhereHas('pengguna', function ($sub) use ($search): void {
                        $sub->where('nama_lengkap', 'like', "%{$search}%");
                    });
            });
        }

        $pengajuanList = $query->latest('updated_at')->paginate(10)->withQueryString();

        return view('finance.antrean_pencairan', compact('pengajuanList', 'search'));
    }

    /**
     * Proses unggah bukti pencairan oleh Finance.
     */
    public function uploadBuktiPencairan(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'bukti_pencairan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // maks 5MB
        ]);

        $pengajuan = PengajuanRab::findOrFail($id);

        if ($pengajuan->status !== StatusPengajuan::PROSES_PENCAIRAN) {
            abort(422, 'Pengajuan ini tidak dalam status Proses Pencairan.');
        }

        if ($request->hasFile('bukti_pencairan')) {
            $file = $request->file('bukti_pencairan');
            $path = $file->store('bukti_pencairan', 'public');

            $pengajuan->update([
                'bukti_pencairan' => $path,
                'status' => StatusPengajuan::SELESAI,
            ]);

            ActivityLog::log("Mengunggah bukti pencairan untuk Pengajuan RAB {$pengajuan->no_rab}.");

            return redirect()->route('finance.pencairan')->with('success', 'Bukti pencairan berhasil diunggah. Pengajuan telah selesai.');
        }

        return back()->withErrors(['bukti_pencairan' => 'Gagal mengunggah bukti pencairan.']);
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
     * Proses keputusan persetujuan Tahap 1 oleh Finance.
     */
    public function processApproval(Request $request, int $id): RedirectResponse
    {
        // PRESENTASI: Memisahkan Logika Revisi dan Ditolak Permanen di level Finance
        $rawStatus = (string) ($request->input('action') ?? $request->input('status') ?? '');
        
        $statusDecision = 'ACC'; // Default
        if (strcasecmp($rawStatus, 'revisi') === 0) {
            $statusDecision = 'Revisi';
        } elseif (strcasecmp($rawStatus, 'ditolak') === 0 || str_contains(strtolower($rawStatus), 'tolak')) {
            $statusDecision = 'Ditolak';
        }

        $request->merge(['status_decision' => $statusDecision]);

        // PRESENTASI: Catatan diwajibkan jika keputusan adalah Revisi atau Ditolak
        $validated = $request->validate([
            'status_decision' => ['required', 'in:ACC,Revisi,Ditolak'],
            'catatan' => [
                $statusDecision === 'ACC' ? 'nullable' : 'required', 
                'string', 
                'max:2000'
            ],
        ], [
            'status_decision.required' => 'Keputusan persetujuan Finance wajib ditentukan.',
            'catatan.required' => 'Catatan/Evaluasi wajib diisi untuk penolakan atau revisi.',
        ]);

        $reviewerId = (int) Auth::id();

        DB::transaction(function () use ($id, $reviewerId, $validated): void {
            $pengajuan = PengajuanRab::lockForUpdate()->findOrFail($id);

            // Validasi state
            if ($pengajuan->status !== StatusPengajuan::MENUNGGU_FINANCE) {
                abort(422, 'Pengajuan ini tidak dalam status Menunggu Verifikasi Finance.');
            }

            // PRESENTASI: Menentukan target status berdasarkan aksi yang dipilih
            if ($validated['status_decision'] === 'ACC') {
                $targetStatus = StatusPengajuan::MENUNGGU_PIMPINAN;
            } elseif ($validated['status_decision'] === 'Revisi') {
                $targetStatus = StatusPengajuan::REVISI;
            } else {
                $targetStatus = StatusPengajuan::DITOLAK;
            }

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
                'catatan' => $validated['catatan'] ?? ($validated['status_decision'] === 'ACC' ? 'ACC Tahap 1 oleh Finance' : 'Revisi/Ditolak pada verifikasi Finance'),
                'tanggal_proses' => now(),
            ]);
        });

        // PRESENTASI: Pesan sukses yang disesuaikan berdasarkan aksi
        $message = 'Pengajuan RAB berhasil diproses.';
        if ($validated['status_decision'] === 'ACC') {
            $message = 'Pengajuan RAB berhasil di-ACC Finance dan diteruskan ke Pimpinan.';
        } elseif ($validated['status_decision'] === 'Revisi') {
            $message = 'Pengajuan RAB dikembalikan ke Staff untuk direvisi.';
        } else {
            $message = 'Pengajuan RAB telah ditolak permanen oleh Finance.';
        }

        ActivityLog::log("Melakukan verifikasi Tahap 1 (Finance) pada Pengajuan RAB #{$id} dengan keputusan {$validated['status_decision']}.");

        return redirect()->route('finance.antrean')->with('success', $message);
    }

    /**
     * Tampilkan riwayat pengajuan yang telah diproses oleh Finance.
     */
    public function riwayat(Request $request): View
    {
        $statusFilter = $request->input('status');
        $search = $request->input('q');

        $query = PengajuanRab::with(['pengguna', 'divisi', 'alurPersetujuan.reviewer'])
            ->whereIn('status', [
                StatusPengajuan::MENUNGGU_PIMPINAN,
                StatusPengajuan::REVISI,
                StatusPengajuan::PROSES_PENCAIRAN,
                StatusPengajuan::DITOLAK,
                StatusPengajuan::SELESAI,
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

        return view('finance.riwayat', compact('pengajuanList', 'statusFilter', 'search'));
    }
}
