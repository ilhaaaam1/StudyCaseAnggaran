<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\KategoriAnggaran;

class KategoriAnggaranController extends Controller
{
    /**
     * Menampilkan halaman Master Kategori & Pagu Anggaran
     */
    public function index(Request $request)
    {
        $kategoriList = KategoriAnggaran::latest()->get();
        return view('finance.kategori.index', compact('kategoriList'));
    }

    /**
     * Menyimpan kategori & pagu baru
     */
    public function store(Request $request)
    {
        // PRESENTASI: Logika Tambah Data (Create) Master Pagu Anggaran
        // Memvalidasi data yang dikirim dan menyimpannya ke database
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_anggarans',
            'deskripsi' => 'nullable|string',
            'pagu_anggaran' => 'required|numeric|min:0',
        ]);

        KategoriAnggaran::create($validated);

        return redirect()->route('finance.kategori.index')->with('success', 'Kategori anggaran berhasil ditambahkan.');
    }

    /**
     * Memperbarui data kategori & pagu
     */
    public function update(Request $request, $id)
    {
        // PRESENTASI: Logika Edit Data (Update) Master Pagu Anggaran
        // Menerima input dari modal edit dan memperbarui data yang bersesuaian di database
        $kategori = KategoriAnggaran::findOrFail($id);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_anggarans,nama_kategori,' . $kategori->id,
            'deskripsi' => 'nullable|string',
            'pagu_anggaran' => 'required|numeric|min:0',
        ]);

        $kategori->update($validated);

        return redirect()->route('finance.kategori.index')->with('success', 'Kategori anggaran berhasil diperbarui.');
    }

    /**
     * Menghapus kategori anggaran
     */
    public function destroy($id)
    {
        // PRESENTASI: Logika Hapus Data (Delete) Master Pagu Anggaran
        $kategori = KategoriAnggaran::findOrFail($id);
        $kategori->delete();

        return redirect()->route('finance.kategori.index')->with('success', 'Kategori anggaran berhasil dihapus.');
    }
}
