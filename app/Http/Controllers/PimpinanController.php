<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\StatusPengajuan;
use App\Models\ActivityLog;
use App\Models\AlurPersetujuan;
use App\Models\DelegationAuthority;
use App\Models\PengajuanRab;
use App\Models\Pengguna;
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
     * Proses keputusan persetujuan Final (Tahap 2) oleh Pimpinan atau Delegasi.
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

        $user = Auth::user();
        $pimpinanId = (int) $user->id_pengguna;

        // PRESENTASI: Pengecekan otorisasi delegasi dalam Controller Pimpinan
        // Jika user yang mengeksekusi bukan ber-role pimpinan, kita anggap ia adalah penerima delegasi.
        $isDelegated = $user->role !== 'pimpinan' && $user->hasActiveDelegation();

        DB::transaction(function () use ($id, $pimpinanId, $validated, $isDelegated, $user): void {
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

            // PRESENTASI: Modifikasi pencatatan log approval agar mencerminkan tindakan delegasi
            // Jika didelegasikan, kita menambahkan teks "(Atas nama Pimpinan)" ke dalam catatan sistem.
            $defaultCatatan = $validated['status_decision'] === 'ACC'
                ? 'Persetujuan Final oleh Pimpinan'
                : 'Ditolak oleh Pimpinan pada tahap final';

            if ($isDelegated) {
                $defaultCatatan = $validated['status_decision'] === 'ACC'
                    ? 'Disetujui oleh '.$user->nama_lengkap.' (Atas nama Pimpinan)'
                    : 'Ditolak oleh '.$user->nama_lengkap.' (Atas nama Pimpinan)';
            }

            $catatanText = ! empty($validated['catatan'])
                ? $validated['catatan'].($isDelegated ? "\n[Diproses Atas Nama Pimpinan]" : '')
                : $defaultCatatan;

            // 2. Catat log persetujuan level 2 di alur_persetujuan
            AlurPersetujuan::create([
                'id_pengajuan' => $id,
                'id_reviewer' => $pimpinanId,
                'level_persetujuan' => 2,
                'status_persetujuan' => $validated['status_decision'],
                'catatan' => $catatanText,
                'tanggal_proses' => now(),
            ]);
        });

        $message = $validated['status_decision'] === 'ACC'
            ? 'Pengajuan RAB berhasil disetujui dan diteruskan ke Finance untuk Proses Pencairan.'
            : 'Pengajuan RAB telah ditolak oleh Pimpinan.';

        $logMsg = $isDelegated
            ? "Melakukan verifikasi Final (Atas nama Pimpinan) pada Pengajuan RAB #{$id} dengan keputusan {$validated['status_decision']}."
            : "Melakukan verifikasi Final (Pimpinan) pada Pengajuan RAB #{$id} dengan keputusan {$validated['status_decision']}.";

        ActivityLog::log($logMsg);

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

    /**
     * Tampilkan statistik dan laporan penyerapan anggaran.
     */
    public function statistik(Request $request): View
    {
        $filterWaktu = $request->input('filter_waktu', 'bulan_ini');

        $baseQuery = PengajuanRab::query();
        $now = now();

        if ($filterWaktu === 'bulan_ini') {
            $baseQuery->whereMonth('tanggal_pengajuan', $now->month)
                ->whereYear('tanggal_pengajuan', $now->year);
        } elseif ($filterWaktu === 'kuartal_ini') {
            $baseQuery->whereRaw('QUARTER(tanggal_pengajuan) = ?', [$now->quarter])
                ->whereYear('tanggal_pengajuan', $now->year);
        } elseif ($filterWaktu === 'tahun_ini') {
            $baseQuery->whereYear('tanggal_pengajuan', $now->year);
        }

        // Summary Cards
        $totalDisetujui = (clone $baseQuery)->whereIn('status', [StatusPengajuan::PROSES_PENCAIRAN, StatusPengajuan::SELESAI])->sum('estimasi_total');
        $totalDicairkan = (clone $baseQuery)->where('status', StatusPengajuan::SELESAI)->sum('estimasi_total');
        $totalMenunggu = (clone $baseQuery)->where('status', StatusPengajuan::MENUNGGU_PIMPINAN)->sum('estimasi_total');
        $totalDitolak = (clone $baseQuery)->where('status', StatusPengajuan::DITOLAK)->sum('estimasi_total');

        // Data Grafik Penyerapan per Bulan (Tahun Berjalan)
        $chartBulanDisetujui = PengajuanRab::selectRaw('MONTH(tanggal_pengajuan) as bulan, SUM(estimasi_total) as total')
            ->whereIn('status', [StatusPengajuan::PROSES_PENCAIRAN, StatusPengajuan::SELESAI])
            ->whereYear('tanggal_pengajuan', $now->year)
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $chartBulanDicairkan = PengajuanRab::selectRaw('MONTH(tanggal_pengajuan) as bulan, SUM(estimasi_total) as total')
            ->where('status', StatusPengajuan::SELESAI)
            ->whereYear('tanggal_pengajuan', $now->year)
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $dataPenyerapanDisetujui = [];
        $dataPenyerapanDicairkan = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataPenyerapanDisetujui[] = $chartBulanDisetujui[$i] ?? 0;
            $dataPenyerapanDicairkan[] = $chartBulanDicairkan[$i] ?? 0;
        }

        // Data Alokasi per Divisi (berdasarkan filter waktu)
        $chartDivisi = (clone $baseQuery)
            ->selectRaw('id_divisi, SUM(estimasi_total) as total')
            ->whereIn('status', [StatusPengajuan::PROSES_PENCAIRAN, StatusPengajuan::SELESAI])
            ->groupBy('id_divisi')
            ->with('divisi')
            ->get();

        $labelDivisi = [];
        $dataDivisi = [];
        foreach ($chartDivisi as $item) {
            $labelDivisi[] = $item->divisi->nama_divisi ?? 'Unknown';
            $dataDivisi[] = $item->total;
        }

        // Riwayat Pencairan Terkini
        $riwayatPencairan = PengajuanRab::with(['divisi'])
            ->where('status', StatusPengajuan::SELESAI)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('pimpinan.statistik', compact(
            'filterWaktu',
            'totalDisetujui',
            'totalDicairkan',
            'totalMenunggu',
            'totalDitolak',
            'dataPenyerapanDisetujui',
            'dataPenyerapanDicairkan',
            'labelDivisi',
            'dataDivisi',
            'riwayatPencairan'
        ));
    }

    /**
     * Tampilkan halaman Delegasi Wewenang.
     */
    public function delegasiIndex(Request $request): View
    {
        $delegations = DelegationAuthority::with('delegateTo')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil semua pengguna selain diri sendiri untuk dropdown
        $users = Pengguna::where('id_pengguna', '!=', Auth::id())->get();

        return view('pimpinan.delegasi', compact('delegations', 'users'));
    }

    /**
     * Simpan delegasi wewenang baru.
     */
    public function delegasiStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'delegate_to_user_id' => 'required|exists:pengguna,id_pengguna',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'Aktif';

        DelegationAuthority::create($validated);

        return redirect()->route('pimpinan.delegasi.index')->with('success', 'Delegasi wewenang berhasil ditambahkan.');
    }

    /**
     * Batalkan delegasi (Ubah status jadi Dibatalkan).
     */
    public function delegasiCancel(int $id): RedirectResponse
    {
        $delegation = DelegationAuthority::where('user_id', Auth::id())->findOrFail($id);
        $delegation->update(['status' => 'Dibatalkan']);

        return redirect()->route('pimpinan.delegasi.index')->with('success', 'Delegasi wewenang berhasil dibatalkan.');
    }

    /**
     * Hapus permanen riwayat delegasi.
     */
    public function delegasiDestroy(int $id): RedirectResponse
    {
        $delegation = DelegationAuthority::where('user_id', Auth::id())->findOrFail($id);
        $delegation->delete();

        return redirect()->route('pimpinan.delegasi.index')->with('success', 'Riwayat delegasi berhasil dihapus.');
    }
}
