<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\KategoriAnggaran;
use App\Models\PengajuanRab;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

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
                return $q->where('kategori_anggaran', 'like', '%'.$kategori.'%');
            });

        $rekapList = $query->latest('tanggal_pengajuan')->get();

        // Load view khusus PDF dan passing data
        $pdf = Pdf::loadView('finance.rekapitulasi.pdf', compact('rekapList'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('Laporan_Rekapitulasi_SIRAB.pdf');
    }
}
