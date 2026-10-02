<?php

namespace App\Http\Controllers;

use App\Enums\StatusPengajuan;
use App\Models\KategoriAnggaran;
use Illuminate\Http\Request;

class KategoriAnggaranController extends Controller
{
    public function index(Request $request)
    {
        // PRESENTASI: Implementasi withSum Eloquent untuk menghitung realisasi anggaran secara efisien tanpa N+1 query problem
        $kategoriList = KategoriAnggaran::withSum(['pengajuanRabs' => function ($q) {
            $q->whereIn('status', [
                StatusPengajuan::MENUNGGU_FINANCE,
                StatusPengajuan::MENUNGGU_PIMPINAN,
                StatusPengajuan::PROSES_PENCAIRAN,
                StatusPengajuan::SELESAI,
            ]);
        }], 'estimasi_total')->latest()->get();

        return view('finance.kategori.index', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_anggarans',
            'deskripsi' => 'nullable|string',
            'pagu_anggaran' => 'required|numeric|min:0',
        ]);

        KategoriAnggaran::create($validated);

        return redirect()->route('finance.kategori.index')->with('success', 'Kategori anggaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriAnggaran::findOrFail($id);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_anggarans,nama_kategori,'.$kategori->id,
            'deskripsi' => 'nullable|string',
            'pagu_anggaran' => 'required|numeric|min:0',
        ]);

        $kategori->update($validated);

        return redirect()->route('finance.kategori.index')->with('success', 'Kategori anggaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // PRESENTASI: Logika Hapus Data (Delete) Master Pagu Anggaran
        $kategori = KategoriAnggaran::findOrFail($id);
        $kategori->delete();

        return redirect()->route('finance.kategori.index')->with('success', 'Kategori anggaran berhasil dihapus.');
    }
}
