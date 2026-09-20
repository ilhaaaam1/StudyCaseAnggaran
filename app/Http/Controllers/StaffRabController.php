<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\StatusPengajuan;
use App\Http\Requests\StoreRabRequest;
use App\Models\ActivityLog;
use App\Models\Divisi;
use App\Models\DokumenPendukung;
use App\Models\PengajuanRab;
use App\Models\RincianItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        $totalPending = PengajuanRab::where('id_pengguna', $userId)->where('status', StatusPengajuan::MENUNGGU_FINANCE)->count();
        $totalAccFinance = PengajuanRab::where('id_pengguna', $userId)->where('status', StatusPengajuan::MENUNGGU_PIMPINAN)->count();
        $totalAccFinal = PengajuanRab::where('id_pengguna', $userId)->whereIn('status', [StatusPengajuan::PROSES_PENCAIRAN, StatusPengajuan::SELESAI])->count();
        $totalDitolak = PengajuanRab::where('id_pengguna', $userId)
            ->whereIn('status', [StatusPengajuan::REVISI, StatusPengajuan::DITOLAK])
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
     * Simpan pengajuan RAB baru (Status awal: 'Menunggu Verifikasi Finance').
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

            $status = ($request->input('action') === 'draft') ? StatusPengajuan::DRAFT : StatusPengajuan::MENUNGGU_FINANCE;

            // 1. Simpan data header pengajuan RAB dengan status awal
            $pengajuanRab = PengajuanRab::create([
                'id_pengguna' => $user->id_pengguna,
                'id_divisi' => (int) $request->input('id_divisi'),
                'no_rab' => $noRab,
                'judul_pengajuan' => $request->input('judul_pengajuan'),
                'periode_penggunaan' => $request->input('periode_penggunaan'),
                'prioritas' => $request->input('prioritas'),
                'latar_belakang' => $request->input('latar_belakang'),
                'estimasi_total' => 0,
                'status' => $status,
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

        $msg = ($request->input('action') === 'draft')
            ? "Draft RAB {$pengajuan->no_rab} berhasil disimpan."
            : "Pengajuan RAB {$pengajuan->no_rab} berhasil dibuat dengan status Menunggu Verifikasi Finance.";

        ActivityLog::log($msg);

        $redirectRoute = ($request->input('action') === 'draft') ? 'staff.draft' : 'staff.riwayat';

        return redirect()->route($redirectRoute)
            ->with('success', $msg);
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

    /**
     * Tampilkan daftar draft pengajuan RAB.
     */
    public function draft(Request $request): View
    {
        $userId = (int) Auth::id();

        $draftList = PengajuanRab::with(['divisi'])
            ->where('id_pengguna', $userId)
            ->where('status', StatusPengajuan::DRAFT)
            ->latest('tanggal_pengajuan')
            ->paginate(10);

        return view('staff.draft', compact('draftList'));
    }

    /**
     * Tampilkan form edit RAB.
     */
    public function edit(int $id): View
    {
        $userId = (int) Auth::id();

        $pengajuan = PengajuanRab::with(['rincianItem'])
            ->where('id_pengguna', $userId)
            ->whereIn('status', [StatusPengajuan::DRAFT, StatusPengajuan::REVISI])
            ->findOrFail($id);

        $divisiList = Divisi::orderBy('nama_divisi')->get();

        return view('staff.edit', compact('pengajuan', 'divisiList'));
    }

    /**
     * Update pengajuan RAB yang sudah ada (dari Draft / Revisi).
     */
    public function update(StoreRabRequest $request, int $id): RedirectResponse
    {
        $userId = (int) Auth::id();

        $pengajuan = PengajuanRab::where('id_pengguna', $userId)
            ->whereIn('status', [StatusPengajuan::DRAFT, StatusPengajuan::REVISI])
            ->findOrFail($id);

        DB::transaction(function () use ($request, $pengajuan) {
            $status = ($request->input('action') === 'draft') ? StatusPengajuan::DRAFT : StatusPengajuan::MENUNGGU_FINANCE;

            // 1. Update data header pengajuan RAB
            $pengajuan->update([
                'id_divisi' => (int) $request->input('id_divisi'),
                'judul_pengajuan' => $request->input('judul_pengajuan'),
                'periode_penggunaan' => $request->input('periode_penggunaan'),
                'prioritas' => $request->input('prioritas'),
                'latar_belakang' => $request->input('latar_belakang'),
                'status' => $status,
                'tanggal_pengajuan' => ($status === StatusPengajuan::MENUNGGU_FINANCE && $pengajuan->status === StatusPengajuan::DRAFT) ? now() : $pengajuan->tanggal_pengajuan,
            ]);

            // 2. Hapus rincian item lama, simpan rincian item baru
            $pengajuan->rincianItem()->delete();

            $totalEstimasi = 0;
            $items = $request->input('items', []);

            foreach ($items as $item) {
                $vol = (float) $item['volume'];
                $hrg = (float) $item['harga_satuan'];
                $subtotal = $vol * $hrg;
                $totalEstimasi += $subtotal;

                RincianItem::create([
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'uraian_barang' => $item['uraian_barang'],
                    'satuan' => $item['satuan'],
                    'volume' => $vol,
                    'harga_satuan' => $hrg,
                    'total_harga' => $subtotal,
                ]);
            }

            // Update total estimasi
            $pengajuan->update(['estimasi_total' => $totalEstimasi]);

            // 3. Simpan dokumen pendukung baru (jika ada upload)
            if ($request->hasFile('dokumen_pendukung')) {
                // Delete old ones
                foreach ($pengajuan->dokumenPendukung as $doc) {
                    Storage::disk('public')->delete($doc->path_file);
                    $doc->delete();
                }

                $file = $request->file('dokumen_pendukung');
                $namaAsli = $file->getClientOriginalName();
                $path = $file->store('dokumen_rab', 'public');

                DokumenPendukung::create([
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'nama_file' => $namaAsli,
                    'tipe_dokumen' => $file->getClientMimeType(),
                    'path_file' => $path,
                    'waktu_unggah' => now(),
                ]);
            }
        });

        $msg = ($request->input('action') === 'draft')
            ? "Draft RAB {$pengajuan->no_rab} berhasil diperbarui."
            : "Pengajuan RAB {$pengajuan->no_rab} berhasil dikirim ke Finance.";

        $redirectRoute = ($request->input('action') === 'draft') ? 'staff.draft' : 'staff.riwayat';

        return redirect()->route($redirectRoute)->with('success', $msg);
    }

    /**
     * Hapus draft pengajuan RAB.
     */
    public function destroy(int $id): RedirectResponse
    {
        $userId = (int) Auth::id();

        $pengajuan = PengajuanRab::where('id_pengguna', $userId)
            ->where('status', StatusPengajuan::DRAFT)
            ->findOrFail($id);

        DB::transaction(function () use ($pengajuan) {
            foreach ($pengajuan->dokumenPendukung as $doc) {
                Storage::disk('public')->delete($doc->path_file);
            }
            $pengajuan->delete();
        });

        return redirect()->route('staff.draft')->with('success', "Draft RAB {$pengajuan->no_rab} berhasil dihapus.");
    }

    /**
     * Tampilkan panduan / SOP pengajuan RAB.
     */
    public function panduan(): View
    {
        return view('staff.panduan');
    }
}
