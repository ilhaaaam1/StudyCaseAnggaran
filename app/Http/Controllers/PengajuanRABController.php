<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePengajuanRabRequest;
use App\Models\AlurPersetujuan;
use App\Models\Divisi;
use App\Models\PengajuanRab;
use App\Models\Pengguna;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanRABController extends Controller
{
    /**
     * Menampilkan daftar riwayat Pengajuan RAB (Read All).
     * Mencegah N+1 Query Problem menggunakan Eager Loading (with).
     */
    public function index(Request $request): View
    {
        $pengajuanList = PengajuanRab::with(['pengguna', 'divisi', 'rincianItem'])
            ->latest('tanggal_pengajuan')
            ->paginate(10);

        return view('pengajuan.index', compact('pengajuanList'));
    }

    /**
     * Menampilkan form pembuatan Pengajuan RAB baru (Create Form).
     */
    public function create(): View
    {
        $divisiList = Divisi::orderBy('nama_divisi')->get();
        $penggunaList = Pengguna::with('divisi')->orderBy('nama_lengkap')->get();

        // Generate Nomor RAB otomatis (format: RAB-YYYY-XXX)
        $year = date('Y');
        $lastRab = PengajuanRab::where('no_rab', 'like', "RAB-{$year}-%")->latest('id_pengajuan')->first();
        $nextNumber = 1;
        if ($lastRab && preg_match("/RAB-{$year}-(\d+)/", $lastRab->no_rab, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        }
        $noRabOtomatis = sprintf('RAB-%s-%03d', $year, $nextNumber);

        $periodeList = [
            'Q1 '.date('Y'),
            'Q2 '.date('Y'),
            'Q3 '.date('Y'),
            'Q4 '.date('Y'),
        ];

        return view('pengajuan.create', compact('divisiList', 'penggunaList', 'noRabOtomatis', 'periodeList'));
    }

    /**
     * Menyimpan Pengajuan RAB baru beserta rincian item & dokumen pendukung (Nested Insert).
     */
    public function store(StorePengajuanRabRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $pengajuan = DB::transaction(function () use ($validated, $request) {
            // 1. Hitung total dari seluruh baris rincian item
            $estimasiTotal = 0.0;
            foreach ($validated['rincian'] as $item) {
                $estimasiTotal += (int) $item['volume'] * (float) $item['harga_satuan'];
            }

            // 2. Simpan Data Induk Pengajuan RAB
            $pengajuanRab = PengajuanRab::create([
                'id_pengguna' => (int) $validated['id_pengguna'],
                'id_divisi' => (int) $validated['id_divisi'],
                'no_rab' => $validated['no_rab'],
                'judul_pengajuan' => $validated['judul_pengajuan'],
                'periode_penggunaan' => $validated['periode_penggunaan'],
                'prioritas' => $validated['prioritas'],
                'latar_belakang' => $validated['latar_belakang'],
                'estimasi_total' => $estimasiTotal,
                'status' => $validated['status'] ?? 'diajukan',
                'tanggal_pengajuan' => now(),
            ]);

            // 3. Simpan Tabel Anak: Rincian Item (Nested Insert berelasi id_pengajuan)
            foreach ($validated['rincian'] as $item) {
                $volume = (int) $item['volume'];
                $hargaSatuan = (float) $item['harga_satuan'];
                $totalHarga = $volume * $hargaSatuan;

                $pengajuanRab->rincianItem()->create([
                    'uraian_barang' => $item['uraian_barang'],
                    'satuan' => $item['satuan'],
                    'volume' => $volume,
                    'harga_satuan' => $hargaSatuan,
                    'total_harga' => $totalHarga,
                ]);
            }

            // 4. Simpan Tabel Anak: Dokumen Pendukung (Upload File)
            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $file) {
                    if ($file->isValid()) {
                        $namaFile = $file->getClientOriginalName();
                        $tipeDokumen = $file->getClientOriginalExtension() ?: 'file';
                        $pathFile = $file->store('dokumen_pendukung', 'public');

                        $pengajuanRab->dokumenPendukung()->create([
                            'nama_file' => $namaFile,
                            'tipe_dokumen' => $tipeDokumen,
                            'path_file' => $pathFile,
                            'waktu_unggah' => now(),
                        ]);
                    }
                }
            }

            return $pengajuanRab;
        });

        $pesan = $pengajuan->status === 'diajukan'
            ? "Pengajuan RAB ({$pengajuan->no_rab}) berhasil diajukan untuk proses reviu!"
            : "Pengajuan RAB ({$pengajuan->no_rab}) berhasil disimpan sebagai Draft.";

        // Menggunakan custom primary key id_pengajuan
        return redirect()->route('pengajuan.index')->with('success', $pesan);
    }

    /**
     * Menampilkan detail Pengajuan RAB berdasarkan custom primary key id_pengajuan.
     */
    public function show(int $id_pengajuan): View
    {
        $pengajuan = PengajuanRab::with(['pengguna', 'divisi', 'rincianItem', 'dokumenPendukung', 'alurPersetujuan.reviewer'])
            ->where('id_pengajuan', $id_pengajuan)
            ->firstOrFail();

        return view('pengajuan.show', compact('pengajuan'));
    }

    /**
     * Menampilkan daftar antrean pengajuan RAB yang menunggu persetujuan Admin (status = diajukan).
     */
    public function antreanPersetujuan(Request $request): View
    {
        $pendingList = PengajuanRab::with(['pengguna', 'divisi', 'rincianItem'])
            ->where('status', 'diajukan')
            ->latest('tanggal_pengajuan')
            ->paginate(10);

        $pendingCount = PengajuanRab::where('status', 'diajukan')->count();
        $approvedCount = PengajuanRab::where('status', 'disetujui')->count();
        $rejectedCount = PengajuanRab::whereIn('status', ['ditolak', 'revisi'])->count();

        return view('pengajuan.persetujuan', compact('pendingList', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    /**
     * Memproses persetujuan pengajuan RAB oleh Admin (Approval Logic).
     * Menggunakan DB::transaction() untuk menjamin integritas data saat meng-update status
     * dan mencatat riwayat ke tabel alur_persetujuan secara bersamaan.
     */
    public function processApproval(Request $request, int $id_pengajuan): RedirectResponse
    {
        $validated = $request->validate([
            'status_persetujuan' => ['required', 'in:disetujui,ditolak,revisi'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $pengajuan = PengajuanRab::where('id_pengajuan', $id_pengajuan)->firstOrFail();

        // Ambil ID pengguna reviewer (Admin) yang sedang memproses
        $reviewerId = null;
        if (Auth::check()) {
            $reviewerId = Pengguna::where('email', Auth::user()->email)->value('id_pengguna');
        }

        if (! $reviewerId) {
            $reviewerId = Pengguna::where('jabatan', 'like', '%Direktur%')
                ->orWhere('jabatan', 'like', '%Admin%')
                ->value('id_pengguna') ?? 1;
        }

        DB::transaction(function () use ($pengajuan, $validated, $reviewerId) {
            // a. UPDATE status di tabel pengajuan_rab
            $pengajuan->update([
                'status' => $validated['status_persetujuan'],
            ]);

            // b. INSERT log riwayat ke tabel alur_persetujuan
            AlurPersetujuan::create([
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'id_reviewer' => $reviewerId,
                'level_persetujuan' => 1,
                'status_persetujuan' => $validated['status_persetujuan'],
                'catatan' => $validated['catatan'] ?? null,
                'tanggal_proses' => now(),
            ]);
        });

        $statusText = match ($validated['status_persetujuan']) {
            'disetujui' => 'disetujui',
            'ditolak' => 'ditolak',
            'revisi' => 'dikembalikan untuk revisi',
            default => $validated['status_persetujuan'],
        };

        return redirect()->back()->with('success', "Pengajuan RAB ({$pengajuan->no_rab}) berhasil {$statusText}.");
    }
}
