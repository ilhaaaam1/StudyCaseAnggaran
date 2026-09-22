<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\StatusPengajuan;
use App\Http\Requests\StoreRabRequest;
use App\Models\Divisi;
use App\Models\DokumenPendukung;
use App\Models\PengajuanRab;
use App\Models\RincianItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserRabController extends Controller
{
    /**
     * Tampilkan dashboard user (hanya hitung & tampilkan data milik user yang login).
     */
    public function index(Request $request): View
    {
        $userId = Auth::id();

        $stats = PengajuanRab::where('id_pengguna', $userId)
            ->selectRaw('
                COUNT(*) as total_pengajuan,
                SUM(CASE WHEN status = "Pending" OR status = ? THEN 1 ELSE 0 END) as total_pending,
                SUM(CASE WHEN status = "ACC" OR status IN (?, ?) THEN 1 ELSE 0 END) as total_acc,
                SUM(CASE WHEN status = "Ditolak" OR status = ? THEN 1 ELSE 0 END) as total_ditolak,
                COALESCE(SUM(estimasi_total), 0) as total_anggaran
            ', [
                StatusPengajuan::MENUNGGU_FINANCE->value,
                StatusPengajuan::PROSES_PENCAIRAN->value,
                StatusPengajuan::SELESAI->value,
                StatusPengajuan::DITOLAK->value,
            ])
            ->first();

        $totalPengajuan = (int) ($stats->total_pengajuan ?? 0);
        $totalPending = (int) ($stats->total_pending ?? 0);
        $totalAcc = (int) ($stats->total_acc ?? 0);
        $totalDitolak = (int) ($stats->total_ditolak ?? 0);
        $totalAnggaran = (float) ($stats->total_anggaran ?? 0);

        // Daftar pengajuan milik user yang login
        $pengajuanList = PengajuanRab::with(['divisi', 'alurPersetujuan.reviewer'])
            ->where('id_pengguna', $userId)
            ->latest('tanggal_pengajuan')
            ->paginate(10);

        return view('user.dashboard', compact(
            'totalPengajuan',
            'totalPending',
            'totalAcc',
            'totalDitolak',
            'totalAnggaran',
            'pengajuanList'
        ));
    }

    /**
     * Tampilkan form pembuatan RAB baru.
     */
    public function create(): View
    {
        $user = Auth::user();
        $divisiList = Divisi::orderBy('nama_divisi')->get();

        // Generate nomor RAB otomatis
        $year = date('Y');
        $lastRab = PengajuanRab::whereYear('tanggal_pengajuan', $year)->latest('id_pengajuan')->first();
        $nextNumber = $lastRab ? ((int) substr((string) $lastRab->no_rab, -3)) + 1 : 1;
        $autoNoRab = sprintf('RAB-%s-%03d', $year, $nextNumber);

        return view('user.create', compact('divisiList', 'user', 'autoNoRab'));
    }

    /**
     * Simpan pengajuan RAB menggunakan DB::transaction (3 langkah).
     */
    public function store(StoreRabRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $pengajuan = DB::transaction(function () use ($request, $user) {
            // Generate nomor RAB unik
            $year = date('Y');
            $countThisYear = PengajuanRab::whereYear('tanggal_pengajuan', $year)->count() + 1;
            $noRab = sprintf('RAB-%s-%03d', $year, $countThisYear);

            while (PengajuanRab::where('no_rab', $noRab)->exists()) {
                $countThisYear++;
                $noRab = sprintf('RAB-%s-%03d', $year, $countThisYear);
            }

            // 1. Insert data utama ke pengajuan_rab (status 'Pending')
            $pengajuanRab = PengajuanRab::create([
                'id_pengguna' => $user->id_pengguna,
                'id_divisi' => (int) $request->input('id_divisi'),
                'no_rab' => $noRab,
                'judul_pengajuan' => $request->input('judul_pengajuan'),
                'periode_penggunaan' => $request->input('periode_penggunaan'),
                'prioritas' => $request->input('prioritas'),
                'latar_belakang' => $request->input('latar_belakang'),
                'estimasi_total' => 0,
                'status' => 'Pending',
                'tanggal_pengajuan' => now(),
            ]);

            // 2. Looping array rincian item, hitung otomatis total_harga = volume * harga_satuan, simpan ke rincian_item. Update estimasi_total di pengajuan_rab
            $totalEstimasi = 0;
            $itemsData = $request->input('items', []);

            foreach ($itemsData as $item) {
                $volume = (int) $item['volume'];
                $hargaSatuan = (float) $item['harga_satuan'];
                $totalHarga = $volume * $hargaSatuan;

                RincianItem::create([
                    'id_pengajuan' => $pengajuanRab->id_pengajuan,
                    'uraian_barang' => $item['uraian_barang'],
                    'satuan' => $item['satuan'],
                    'volume' => $volume,
                    'harga_satuan' => $hargaSatuan,
                    'total_harga' => $totalHarga,
                ]);

                $totalEstimasi += $totalHarga;
            }

            // Update nilai estimasi_total di pengajuan_rab
            $pengajuanRab->update(['estimasi_total' => $totalEstimasi]);

            // 3. Upload file fisik ke storage/app/public/dokumen_rab dan simpan path ke dokumen_pendukung
            if ($request->hasFile('dokumen')) {
                $file = $request->file('dokumen');
                $namaAsli = $file->getClientOriginalName();
                $ekstensi = $file->getClientOriginalExtension();

                $storedPath = $file->store('dokumen_rab', 'public');

                DokumenPendukung::create([
                    'id_pengajuan' => $pengajuanRab->id_pengajuan,
                    'nama_file' => $namaAsli,
                    'tipe_dokumen' => strtoupper($ekstensi),
                    'path_file' => $storedPath,
                    'waktu_unggah' => now(),
                ]);
            }

            return $pengajuanRab;
        });

        return redirect()->route('user.rab.show', $pengajuan->id_pengajuan)
            ->with('success', 'Pengajuan RAB '.$pengajuan->no_rab.' berhasil dibuat dan menunggu persetujuan.');
    }

    /**
     * Tampilkan detail pengajuan dan status alur persetujuan.
     */
    public function show(int $id): View
    {
        $pengajuan = PengajuanRab::with([
            'divisi',
            'pengguna',
            'rincianItem',
            'dokumenPendukung',
            'alurPersetujuan.reviewer',
        ])->findOrFail($id);

        // Otorisasi: Pastikan user hanya dapat melihat pengajuannya sendiri jika bukan admin
        if (Auth::check() && Auth::user()->role !== 'admin' && $pengajuan->id_pengguna !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk melihat pengajuan RAB ini.');
        }

        return view('user.show', compact('pengajuan'));
    }

    /**
     * Ambil histori pengajuan milik user yang login.
     */
    public function laporan(Request $request): View
    {
        $userId = Auth::id();

        $historiList = PengajuanRab::with(['divisi', 'alurPersetujuan.reviewer'])
            ->where('id_pengguna', $userId)
            ->latest('tanggal_pengajuan')
            ->get();

        return view('user.laporan', compact('historiList'));
    }
}
