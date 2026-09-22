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

        $stats = PengajuanRab::where('id_pengguna', $userId)
            ->selectRaw('
                COUNT(*) as total_pengajuan,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as total_pending,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as total_acc_finance,
                SUM(CASE WHEN status IN (?, ?) THEN 1 ELSE 0 END) as total_acc_final,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as total_ditolak,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as total_revisi,
                COALESCE(SUM(estimasi_total), 0) as total_anggaran_diajukan
            ', [
                StatusPengajuan::MENUNGGU_FINANCE->value,
                StatusPengajuan::MENUNGGU_PIMPINAN->value,
                StatusPengajuan::PROSES_PENCAIRAN->value,
                StatusPengajuan::SELESAI->value,
                StatusPengajuan::DITOLAK->value,
                StatusPengajuan::REVISI->value,
            ])
            ->first();

        $totalPengajuan = (int) ($stats->total_pengajuan ?? 0);
        $totalPending = (int) ($stats->total_pending ?? 0);
        $totalAccFinance = (int) ($stats->total_acc_finance ?? 0);
        $totalAccFinal = (int) ($stats->total_acc_final ?? 0);
        $totalDitolak = (int) ($stats->total_ditolak ?? 0);
        $totalRevisi = (int) ($stats->total_revisi ?? 0);
        $totalAnggaranDiajukan = (float) ($stats->total_anggaran_diajukan ?? 0);

        // PRESENTASI: Query GroupBy dan Aggregate (SUM/COUNT) untuk mendapatkan data statistik nominal per kategori
        $alokasiKategori = PengajuanRab::where('id_pengguna', $userId)
            ->selectRaw('kategori_anggaran, SUM(estimasi_total) as total_rupiah, COUNT(id_pengajuan) as jumlah_dokumen')
            ->groupBy('kategori_anggaran')
            ->get();

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
            'totalRevisi',
            'totalAnggaranDiajukan',
            'alokasiKategori',
            'pengajuanTerbaru'
        ));
    }

    /**
     * Tampilkan form pembuatan pengajuan RAB baru.
     */
    public function create(): View
    {
        $user = Auth::user();
        $divisiList = Divisi::orderBy('id_divisi')->get();

        $year = date('Y');
        $countThisYear = PengajuanRab::whereYear('tanggal_pengajuan', $year)->count() + 1;
        $autoNoRab = sprintf('RAB-%s-%03d', $year, $countThisYear);

        $kategoriList = [
            'Belanja Barang Operasional & ATK' => 'Kertas HVS, spidol, tinta printer, map rapor, perlengkapan administrasi & kelas',
            'Kegiatan Kesiswaan & Lomba' => 'Pramuka, tari, drum band, PHBN/PHBI, transport kontingen, pendaftaran lomba O2SN/FLS2N',
            'Pemeliharaan Sarana & Prasarana' => 'Perbaikan ruang kelas, sanitasi/toilet, meja-kursi, cat, lampu, pompa air, kebersihan',
            'Pengembangan Perpustakaan & Literasi' => 'Pengadaan buku ajar/literasi, inventarisasi buku, pojok baca, sarana perpustakaan',
            'Peningkatan Kompetensi Guru (SDM)' => 'Pelatihan guru, workshop kurikulum, KKG, seminar pengembangan kompetensi pendidik',
            'Langganan Daya & Jasa' => 'Tagihan listrik PLN, internet sekolah, langganan air bersih, dan jasa operasional',
            'Belanja Modal / Alat Elektronik' => 'Proyektor LCD, laptop ANBK, sound system, peralatan elektronik & laboratorium sekolah',
        ];

        return view('staff.create', compact('divisiList', 'user', 'autoNoRab', 'kategoriList'));
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

            $tahunAjaranSemester = $request->input('tahun_ajaran_semester');
            $tahunAjaran = null;
            $semester = null;
            if ($tahunAjaranSemester) {
                $parts = explode('-', $tahunAjaranSemester);
                $tahunAjaran = trim($parts[0] ?? '');
                $semPart = trim($parts[1] ?? '');
                $semester = str_contains(strtolower($semPart), 'genap') ? 'Genap' : 'Ganjil';
            }

            $tahapBos = $request->input('tahap_bos');
            $tanggalMulai = $request->input('tanggal_mulai');
            $tanggalSelesai = $request->input('tanggal_selesai');

            $periodeOtomatis = mb_substr(
                (string) ($request->input('periode_penggunaan') ?: trim("{$tahapBos} ({$tahunAjaranSemester})")),
                0,
                255
            );

            // 1. Simpan data header pengajuan RAB dengan status awal
            $pengajuanRab = PengajuanRab::create([
                'id_pengguna' => $user->id_pengguna,
                'id_divisi' => (int) $request->input('id_divisi'),
                'no_rab' => $noRab,
                'judul_pengajuan' => $request->input('judul_pengajuan'),
                'tahun_ajaran' => $tahunAjaran,
                'semester' => $semester,
                'tahun_ajaran_semester' => $tahunAjaranSemester,
                'tahap_bos' => $tahapBos,
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'periode_penggunaan' => $periodeOtomatis,
                'kategori_anggaran' => $request->input('kategori_anggaran'),
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
     * Tampilkan riwayat seluruh pengajuan RAB milik staf login dengan metrik dan filter kontekstual sekolah.
     */
    public function riwayat(Request $request): View
    {
        $userId = (int) Auth::id();

        // 1. Single-trip aggregate query untuk 4 kartu ringkasan metrik
        $stats = PengajuanRab::where('id_pengguna', $userId)
            ->selectRaw('
                COUNT(*) as total_pengajuan,
                SUM(CASE WHEN status IN (?, ?) THEN 1 ELSE 0 END) as total_diproses,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as total_revisi,
                COALESCE(SUM(CASE WHEN status IN (?, ?) THEN estimasi_total ELSE 0 END), 0) as total_disetujui
            ', [
                StatusPengajuan::MENUNGGU_FINANCE->value,
                StatusPengajuan::MENUNGGU_PIMPINAN->value,
                StatusPengajuan::REVISI->value,
                StatusPengajuan::PROSES_PENCAIRAN->value,
                StatusPengajuan::SELESAI->value,
            ])
            ->first();

        $metrics = [
            'total_pengajuan' => (int) ($stats->total_pengajuan ?? 0),
            'total_diproses' => (int) ($stats->total_diproses ?? 0),
            'total_revisi' => (int) ($stats->total_revisi ?? 0),
            'total_disetujui' => (float) ($stats->total_disetujui ?? 0),
        ];

        // 2. Filter parameter
        $statusFilter = $request->input('status');
        $search = $request->input('q');
        $tahunAjaranSemester = $request->input('tahun_ajaran_semester');
        $tahapBos = $request->input('tahap_bos');
        $kategoriAnggaran = $request->input('kategori_anggaran');

        $query = PengajuanRab::with(['divisi', 'alurPersetujuan.reviewer', 'rincianItem'])
            ->where('id_pengguna', $userId);

        // Filter tab status
        if ($statusFilter && $statusFilter !== 'semua') {
            if ($statusFilter === 'pencairan_selesai' || $statusFilter === 'cair') {
                $query->whereIn('status', [StatusPengajuan::PROSES_PENCAIRAN, StatusPengajuan::SELESAI]);
            } else {
                $query->where('status', $statusFilter);
            }
        }

        // Filter pencarian teks
        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('no_rab', 'like', "%{$search}%")
                    ->orWhere('judul_pengajuan', 'like', "%{$search}%");
            });
        }

        // Filter kontekstual sekolah
        if ($tahunAjaranSemester) {
            $query->where('tahun_ajaran_semester', $tahunAjaranSemester);
        }

        if ($tahapBos) {
            $query->where('tahap_bos', $tahapBos);
        }

        if ($kategoriAnggaran) {
            $query->where('kategori_anggaran', $kategoriAnggaran);
        }

        $pengajuanList = $query->latest('tanggal_pengajuan')->paginate(10)->withQueryString();

        return view('staff.riwayat', compact(
            'pengajuanList',
            'metrics',
            'statusFilter',
            'search',
            'tahunAjaranSemester',
            'tahapBos',
            'kategoriAnggaran'
        ));
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

        // PRESENTASI: Proteksi Kepemilikan (Authorization)
        // Mengecek bahwa yang mengedit adalah user pembuatnya sendiri ($pengajuan->id_pengguna == auth()->id())
        $pengajuan = PengajuanRab::with(['rincianItem'])
            ->where('id_pengguna', $userId)
            ->findOrFail($id);

        // PRESENTASI: Proteksi Akses Edit (Validasi Status)
        // Mengecek logika bahwa Staff HANYA bisa mengedit jika statusnya 'Menunggu Verifikasi Finance'
        // (serta Draft/Revisi). Jika statusnya sudah diproses, akses langsung diblokir.
        if (! in_array($pengajuan->status, [StatusPengajuan::MENUNGGU_FINANCE, StatusPengajuan::DRAFT, StatusPengajuan::REVISI])) {
            abort(403, 'Pengajuan sudah diproses dan tidak dapat diedit');
        }

        $divisiList = Divisi::orderBy('id_divisi')->get();

        $kategoriList = [
            'Belanja Barang Operasional & ATK' => 'Kertas HVS, spidol, tinta printer, map rapor, perlengkapan administrasi & kelas',
            'Kegiatan Kesiswaan & Lomba' => 'Pramuka, tari, drum band, PHBN/PHBI, transport kontingen, pendaftaran lomba O2SN/FLS2N',
            'Pemeliharaan Sarana & Prasarana' => 'Perbaikan ruang kelas, sanitasi/toilet, meja-kursi, cat, lampu, pompa air, kebersihan',
            'Pengembangan Perpustakaan & Literasi' => 'Pengadaan buku ajar/literasi, inventarisasi buku, pojok baca, sarana perpustakaan',
            'Peningkatan Kompetensi Guru (SDM)' => 'Pelatihan guru, workshop kurikulum, KKG, seminar pengembangan kompetensi pendidik',
            'Langganan Daya & Jasa' => 'Tagihan listrik PLN, internet sekolah, langganan air bersih, dan jasa operasional',
            'Belanja Modal / Alat Elektronik' => 'Proyektor LCD, laptop ANBK, sound system, peralatan elektronik & laboratorium sekolah',
        ];

        return view('staff.edit', compact('pengajuan', 'divisiList', 'kategoriList'));
    }

    /**
     * Update pengajuan RAB yang sudah ada (dari Draft / Revisi / Menunggu Verifikasi Finance).
     */
    public function update(StoreRabRequest $request, int $id): RedirectResponse
    {
        $userId = (int) Auth::id();

        // PRESENTASI: Proteksi Kepemilikan (Authorization) pada proses Update
        // Sama seperti edit, kita pastikan data yang di-update milik user tersebut.
        $pengajuan = PengajuanRab::where('id_pengguna', $userId)->findOrFail($id);

        // PRESENTASI: Validasi Status di proses Update (Backend Security)
        // Mencegah eksploitasi jika user memanipulasi request form secara paksa
        if (! in_array($pengajuan->status, [StatusPengajuan::MENUNGGU_FINANCE, StatusPengajuan::DRAFT, StatusPengajuan::REVISI])) {
            abort(403, 'Pengajuan sudah diproses dan tidak dapat diedit');
        }

        DB::transaction(function () use ($request, $pengajuan) {
            $status = ($request->input('action') === 'draft') ? StatusPengajuan::DRAFT : StatusPengajuan::MENUNGGU_FINANCE;

            $tahunAjaranSemester = $request->input('tahun_ajaran_semester');
            $tahunAjaran = null;
            $semester = null;
            if ($tahunAjaranSemester) {
                $parts = explode('-', $tahunAjaranSemester);
                $tahunAjaran = trim($parts[0] ?? '');
                $semPart = trim($parts[1] ?? '');
                $semester = str_contains(strtolower($semPart), 'genap') ? 'Genap' : 'Ganjil';
            }

            $tahapBos = $request->input('tahap_bos');
            $tanggalMulai = $request->input('tanggal_mulai');
            $tanggalSelesai = $request->input('tanggal_selesai');

            $periodeOtomatis = mb_substr(
                (string) ($request->input('periode_penggunaan') ?: trim("{$tahapBos} ({$tahunAjaranSemester})")),
                0,
                255
            );

            // 1. Update data header pengajuan RAB
            $pengajuan->update([
                'id_divisi' => (int) $request->input('id_divisi'),
                'judul_pengajuan' => $request->input('judul_pengajuan'),
                'tahun_ajaran' => $tahunAjaran,
                'semester' => $semester,
                'tahun_ajaran_semester' => $tahunAjaranSemester,
                'tahap_bos' => $tahapBos,
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'periode_penggunaan' => $periodeOtomatis,
                'kategori_anggaran' => $request->input('kategori_anggaran'),
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
