<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PengajuanRab;
use App\Models\KategoriAnggaran;
use App\Models\Divisi;
use Carbon\Carbon;

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
                return $q->where('kategori_anggaran', 'like', '%' . $kategori . '%');
            });

        // Eksekusi query dengan urutan terbaru
        $rekapList = $query->latest('tanggal_pengajuan')->get();

        // Data referensi untuk Dropdown filter
        $kategoriList = KategoriAnggaran::all();
        $divisiList = Divisi::all();

        return view('finance.rekapitulasi.index', compact('rekapList', 'kategoriList', 'divisiList'));
    }

    /**
     * Export data rekapitulasi ke dalam format PDF.
     */
    public function exportPdf(Request $request)
    {
        // PRESENTASI: Logika Cetak PDF dengan mempertahankan parameter Filter data
        // Query builder identik dengan method index() agar hasil cetak akurat 
        // sesuai dengan data yang sedang dilihat user di layar.
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
                return $q->where('kategori_anggaran', 'like', '%' . $kategori . '%');
            });

        $rekapList = $query->latest('tanggal_pengajuan')->get();

        // Load view khusus PDF dan passing data
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('finance.rekapitulasi.pdf', compact('rekapList'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('Laporan_Rekapitulasi_SIRAB.pdf');
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
                return $q->where('kategori_anggaran', 'like', '%' . $kategori . '%');
            });

        $rekapList = $query->latest('tanggal_pengajuan')->get();

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\RekapitulasiExport($rekapList), 'Rekapitulasi_Laporan_SIRAB.xlsx');
    }
}
