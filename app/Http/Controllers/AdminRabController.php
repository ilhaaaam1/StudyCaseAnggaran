<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AlurPersetujuan;
use App\Models\Divisi;
use App\Models\DokumenPendukung;
use App\Models\PengajuanRab;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminRabController extends Controller
{
    /**
     * Tampilkan dashboard admin dengan agregasi semua data pengajuan.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->input('status');
        $search = $request->input('q');

        $query = PengajuanRab::with(['pengguna', 'divisi', 'alurPersetujuan.reviewer']);

        if ($statusFilter && in_array($statusFilter, ['Pending', 'ACC', 'Ditolak'], true)) {
            $query->where('status', $statusFilter);
        }

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

        // Agregasi metrik riil admin
        $totalPengajuan = PengajuanRab::count();
        $totalPending = PengajuanRab::where('status', 'Pending')->count();
        $totalAcc = PengajuanRab::where('status', 'ACC')->count();
        $totalDitolak = PengajuanRab::where('status', 'Ditolak')->count();
        $totalNominalPengajuan = (float) PengajuanRab::sum('estimasi_total');
        $totalAnggaranAcc = (float) PengajuanRab::where('status', 'ACC')->sum('estimasi_total');
        $totalAnggaranPending = (float) PengajuanRab::where('status', 'Pending')->sum('estimasi_total');
        $totalAnggaranDitolak = (float) PengajuanRab::where('status', 'Ditolak')->sum('estimasi_total');

        return view('admin.dashboard', compact(
            'pengajuanList',
            'totalPengajuan',
            'totalPending',
            'totalAcc',
            'totalDitolak',
            'totalNominalPengajuan',
            'totalAnggaranAcc',
            'totalAnggaranPending',
            'totalAnggaranDitolak',
            'statusFilter',
            'search'
        ));
    }

    /**
     * Ambil semua pengajuan_rab dengan eager loading relasi lengkap untuk daftar dan persetujuan.
     */
    public function approvalList(Request $request): View
    {
        $statusFilter = $request->input('status');
        $search = $request->input('q') ?? $request->input('search');

        $query = PengajuanRab::with([
            'pengguna',
            'rincian_item',
            'dokumen_pendukung',
            'divisi',
            'alurPersetujuan.reviewer',
        ]);

        if ($statusFilter && in_array($statusFilter, ['Pending', 'ACC', 'Ditolak'], true)) {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('no_rab', 'like', "%{$search}%")
                    ->orWhere('judul_pengajuan', 'like', "%{$search}%")
                    ->orWhereHas('pengguna', function ($sub) use ($search): void {
                        $sub->where('nama_lengkap', 'like', "%{$search}%");
                    });
            });
        }

        $pengajuanList = $query->latest('tanggal_pengajuan')->get();

        // Counter untuk filter tab
        $countAll = PengajuanRab::count();
        $countPending = PengajuanRab::where('status', 'Pending')->count();
        $countAcc = PengajuanRab::where('status', 'ACC')->count();
        $countDitolak = PengajuanRab::where('status', 'Ditolak')->count();

        return view('admin.approval', compact(
            'pengajuanList',
            'statusFilter',
            'search',
            'countAll',
            'countPending',
            'countAcc',
            'countDitolak'
        ));
    }

    /**
     * Tampilkan detail pengajuan untuk direview oleh Administrator.
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

        return view('admin.show', compact('pengajuan'));
    }

    /**
     * Eksekusi persetujuan RAB (ACC/Ditolak) dalam DB::transaction().
     */
    public function processApproval(Request $request, int $id): RedirectResponse
    {
        // Normalisasi status keputusan dari status atau status_persetujuan
        $inputStatus = $request->input('status') ?? $request->input('status_persetujuan');
        if (is_string($inputStatus)) {
            if (strcasecmp($inputStatus, 'disetujui') === 0 || strcasecmp($inputStatus, 'ACC') === 0) {
                $inputStatus = 'ACC';
            } elseif (strcasecmp($inputStatus, 'ditolak') === 0) {
                $inputStatus = 'Ditolak';
            }
        }
        $request->merge(['status' => $inputStatus]);

        // Validasi input
        $request->validate([
            'status' => ['required', 'in:ACC,Ditolak'],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ], [
            'status.required' => 'Keputusan persetujuan (ACC / Ditolak) harus dipilih.',
            'status.in' => 'Status keputusan harus berupa ACC atau Ditolak.',
        ]);

        $adminId = (int) (Auth::id() ?? 1);

        DB::transaction(function () use ($request, $id, $adminId, $inputStatus): void {
            $pengajuan = PengajuanRab::lockForUpdate()->findOrFail($id);

            $statusKeputusan = (string) $inputStatus; // 'ACC' atau 'Ditolak'
            $catatan = $request->input('catatan');

            // 1. Update kolom status pada pengajuan_rab
            $pengajuan->update([
                'status' => $statusKeputusan,
            ]);

            // Hitung level persetujuan berikutnya
            $currentMaxLevel = (int) AlurPersetujuan::where('id_pengajuan', $id)->max('level_persetujuan');
            $nextLevel = $currentMaxLevel + 1;

            // 2. Insert record persetujuan ke tabel alur_persetujuan
            AlurPersetujuan::create([
                'id_pengajuan' => $id,
                'id_reviewer' => $adminId,
                'level_persetujuan' => $nextLevel,
                'status_persetujuan' => $statusKeputusan,
                'catatan' => $catatan,
                'tanggal_proses' => now(),
            ]);
        });

        $statusText = $inputStatus === 'ACC' ? 'disetujui (ACC)' : 'ditolak';

        return redirect()->route('admin.dashboard')
            ->with('success', "Pengajuan RAB berhasil diproses dan status telah diubah menjadi {$statusText}.");
    }

    /**
     * Ambil seluruh histori pengajuan yang sudah berstatus final (ACC / Ditolak) atau terfilter.
     */
    public function laporan(Request $request): View
    {
        $divisiFilter = $request->input('id_divisi');
        $statusFilter = $request->input('status');

        $query = PengajuanRab::with(['pengguna', 'divisi', 'dokumenPendukung', 'alurPersetujuan.reviewer']);

        if ($divisiFilter) {
            $query->where('id_divisi', $divisiFilter);
        }

        if ($statusFilter && in_array($statusFilter, ['Pending', 'ACC', 'Ditolak'], true)) {
            $query->where('status', $statusFilter);
        }

        $laporanList = $query->latest('tanggal_pengajuan')->get();

        // Rekapitulasi agregat
        $totalPengajuan = $laporanList->count();
        $totalNominalAcc = $laporanList->where('status', 'ACC')->sum('estimasi_total');
        $totalNominalDitolak = $laporanList->where('status', 'Ditolak')->sum('estimasi_total');
        $totalNominalPending = $laporanList->where('status', 'Pending')->sum('estimasi_total');

        $divisiList = Divisi::orderBy('nama_divisi')->get();

        return view('admin.laporan', compact(
            'laporanList',
            'divisiList',
            'totalPengajuan',
            'totalNominalAcc',
            'totalNominalDitolak',
            'totalNominalPending',
            'divisiFilter',
            'statusFilter'
        ));
    }

    /**
     * Download file fisik dokumen pendukung.
     */
    public function downloadDokumen(int $idDokumen): StreamedResponse
    {
        $dokumen = DokumenPendukung::findOrFail($idDokumen);

        if (! Storage::disk('public')->exists($dokumen->path_file)) {
            abort(404, 'File dokumen pendukung tidak ditemukan di server.');
        }

        return Storage::disk('public')->download($dokumen->path_file, $dokumen->nama_file);
    }
}
