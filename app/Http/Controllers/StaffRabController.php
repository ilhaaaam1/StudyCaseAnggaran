<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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

class StaffRabController extends Controller
{
    /**
     * Tampilkan dashboard Staff dengan metrik pengajuan milik staf yang sedang login.
     */
    public function index(Request $request): View
    {
        $userId = (int) Auth::id();

        $totalPengajuan = PengajuanRab::where('id_pengguna', $userId)->count();
        $totalPending = PengajuanRab::where('id_pengguna', $userId)->where('status', 'Pending')->count();
        $totalAccFinance = PengajuanRab::where('id_pengguna', $userId)->where('status', 'ACC Finance')->count();
        $totalAccFinal = PengajuanRab::where('id_pengguna', $userId)->where('status', 'ACC Final')->count();
        $totalDitolak = PengajuanRab::where('id_pengguna', $userId)
            ->whereIn('status', ['Ditolak Finance', 'Ditolak Pimpinan'])
            ->count();
        $totalAnggaranDiajukan = (float) PengajuanRab::where('id_pengguna', $userId)->sum('estimasi_total');

        $pengajuanTerbaru = PengajuanRab::with(['divisi', 'alurPersetujuan.reviewer'])
            ->where('id_pengguna', $userId)
            ->latest('tanggal_pengajuan')
            ->take(5)
            ->get();

        return view('staff.dashboard', compact(
            'totalPengajuan',
            'totalPending',
            'totalAccFinance',
            'totalAccFinal',
            'totalDitolak',
            'totalAnggaranDiajukan',
            'pengajuanTerbaru'
        ));
    }

    /**
     * Tampilkan form pembuatan pengajuan RAB baru.
     */
    public function create(): View
    {
        $user = Auth::user();
        $divisiList = Divisi::orderBy('nama_divisi')->get();

        $year = date('Y');
        $countThisYear = PengajuanRab::whereYear('tanggal_pengajuan', $year)->count() + 1;
        $autoNoRab = sprintf('RAB-%s-%03d', $year, $countThisYear);

        return view('staff.create', compact('divisiList', 'user', 'autoNoRab'));
    }

    /**
     * Simpan pengajuan RAB baru (Status awal: 'Pending').
     */
    public function store(StoreRabRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $pengajuan = DB::transaction(function () use ($request, $user) {
            $year = date('Y');
            $countThisYear = PengajuanRab::whereYear('tanggal_pengajuan', $year)->count() + 1;
            $noRab = sprintf('RAB-%s-%03d', $year, $countThisYear);

            while (PengajuanRab::where('no_rab', $noRab)->exists()) {
                $countThisYear++;
                $noRab = sprintf('RAB-%s-%03d', $year, $countThisYear);
            }

            // 1. Simpan data header pengajuan RAB dengan status awal 'Pending'
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

            // 2. Simpan rincian item belanja
            $totalEstimasi = 0;
            $items = $request->input('items', []);

            foreach ($items as $item) {
                $vol = (float) $item['volume'];
                $hrg = (float) $item['harga_satuan'];
                $subtotal = $vol * $hrg;
                $totalEstimasi += $subtotal;

                RincianItem::create([
                    'id_pengajuan' => $pengajuanRab->id_pengajuan,
                    'uraian_barang' => $item['uraian_barang'],
                    'satuan' => $item['satuan'],
                    'volume' => $vol,
                    'harga_satuan' => $hrg,
                    'total_harga' => $subtotal,
                ]);
            }

            // Update total estimasi
            $pengajuanRab->update(['estimasi_total' => $totalEstimasi]);

            // 3. Simpan dokumen pendukung (jika ada)
            if ($request->hasFile('dokumen_pendukung')) {
                $file = $request->file('dokumen_pendukung');
                $namaAsli = $file->getClientOriginalName();
                $path = $file->store('dokumen_rab', 'public');

                DokumenPendukung::create([
                    'id_pengajuan' => $pengajuanRab->id_pengajuan,
                    'nama_file' => $namaAsli,
                    'tipe_dokumen' => $file->getClientMimeType(),
                    'path_file' => $path,
                    'waktu_unggah' => now(),
                ]);
            }

            return $pengajuanRab;
        });

        return redirect()->route('staff.riwayat')
            ->with('success', "Pengajuan RAB {$pengajuan->no_rab} berhasil dibuat dengan status Pending dan menunggu verifikasi Finance.");
    }

    /**
     * Tampilkan detail dan tracking alur persetujuan RAB milik staf.
     */
    public function show(int $id): View
    {
        $userId = (int) Auth::id();

        // Staf HANYA boleh melihat RAB miliknya sendiri
        $pengajuan = PengajuanRab::with([
            'divisi',
            'rincianItem',
            'dokumenPendukung',
            'alurPersetujuan.reviewer',
        ])
            ->where('id_pengguna', $userId)
            ->findOrFail($id);

        return view('staff.show', compact('pengajuan'));
    }

    /**
     * Tampilkan riwayat seluruh pengajuan RAB milik staf login.
     */
    public function riwayat(Request $request): View
    {
        $userId = (int) Auth::id();
        $statusFilter = $request->input('status');
        $search = $request->input('q');

        $query = PengajuanRab::with(['divisi', 'alurPersetujuan.reviewer'])
            ->where('id_pengguna', $userId);

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('no_rab', 'like', "%{$search}%")
                    ->orWhere('judul_pengajuan', 'like', "%{$search}%");
            });
        }

        $pengajuanList = $query->latest('tanggal_pengajuan')->paginate(10)->withQueryString();

        return view('staff.riwayat', compact('pengajuanList', 'statusFilter', 'search'));
    }
}
