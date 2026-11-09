<?php

namespace App\Http\Controllers;

use App\Exports\RekapitulasiExport;
use App\Models\Divisi;
use App\Models\KategoriAnggaran;
use App\Models\PengajuanRab;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RekapitulasiController extends Controller
{
    /**
     * Menampilkan halaman rekapitulasi laporan dengan fitur filtering
     */
    public function index(Request $request)
    {
        // PRESENTASI: Logika Filtering Laporan (Backend)
        // Menggunakan Eloquent Query Builder `when()` untuk menyaring data RAB
        // secara dinamis berdasarkan parameter input (tanggal, status, kategori, bidang)
        $query = PengajuanRab::with(['pengguna', 'divisi', 'rincianItem'])
            ->when($request->start_date, function ($q, $startDate) {
                return $q->whereDate('tanggal_pengajuan', '>=', $startDate);
            })
            ->when($request->end_date, function ($q, $endDate) {
                return $q->whereDate('tanggal_pengajuan', '<=', $endDate);
            })
            ->when($request->status, function ($q, $status) {
                return $q->where('status', $status);
            })
            ->when($request->kategori, function ($q, $kategori) {
                return $q->where('kategori_anggaran', 'like', '%'.$kategori.'%');
            });

        // Eksekusi query dengan urutan terbaru
        $rekapList = $query->latest('tanggal_pengajuan')->get();

        // Data referensi untuk Dropdown filter
        $kategoriList = KategoriAnggaran::all();
        $divisiList = Divisi::all();

        return view('finance.rekapitulasi.index', compact('rekapList', 'kategoriList', 'divisiList'));
    }

    /**
     * Export data rekapitulasi ke dalam format PDF (A4 Landscape sesuai Tugas Praktikum).
     */
    public function exportPdf(Request $request)
    {
        // PRESENTASI: Logika Cetak PDF dengan mempertahankan parameter Filter data
        // Menggunakan Eager Loading pengguna, divisi, dan rincianItem untuk mencegah N+1 query problem
        $query = PengajuanRab::with(['pengguna', 'divisi', 'rincianItem'])
            ->when($request->start_date, function ($q, $startDate) {
                return $q->whereDate('tanggal_pengajuan', '>=', $startDate);
            })
            ->when($request->end_date, function ($q, $endDate) {
                return $q->whereDate('tanggal_pengajuan', '<=', $endDate);
            })
            ->when($request->status, function ($q, $status) {
                return $q->where('status', $status);
            })
            ->when($request->kategori, function ($q, $kategori) {
                return $q->where('kategori_anggaran', 'like', '%'.$kategori.'%');
            });

        $rekapList = $query->latest('tanggal_pengajuan')->get();

        // Load view khusus PDF dan konfigurasikan A4 Landscape
        $pdf = Pdf::loadView('finance.rekapitulasi.pdf', compact('rekapList'))
            ->setPaper('a4', 'landscape')
            ->setOption('isRemoteEnabled', true)
            ->setOption('defaultFont', 'sans-serif');

        // Mode stream jika diminta untuk preview browser tab
        if ($request->query('action') === 'stream' || $request->query('stream') === '1') {
            return $pdf->stream('Laporan_Rekapitulasi_SIRAB_'.date('Y-m-d').'.pdf');
        }

        // Default: Response unduhan langsung ke folder download
        return $pdf->download('Laporan_Rekapitulasi_SIRAB_'.date('Y-m-d').'.pdf');
    }

    /**
     * Export data rekapitulasi ke dalam format Excel.
     */
    public function exportExcel(Request $request)
    {
        // PRESENTASI: Logika Filtering Laporan (Backend) untuk Excel
        $query = PengajuanRab::with(['pengguna', 'divisi'])
            ->when($request->start_date, function ($q, $startDate) {
                return $q->whereDate('tanggal_pengajuan', '>=', $startDate);
            })
            ->when($request->end_date, function ($q, $endDate) {
                return $q->whereDate('tanggal_pengajuan', '<=', $endDate);
            })
            ->when($request->status, function ($q, $status) {
                return $q->where('status', $status);
            })
            ->when($request->kategori, function ($q, $kategori) {
                return $q->where('kategori_anggaran', 'like', '%'.$kategori.'%');
            });

        $rekapList = $query->latest('tanggal_pengajuan')->get();

        return Excel::download(new RekapitulasiExport($rekapList), 'Rekapitulasi_Laporan_SIRAB.xlsx');
    }
}
