<?php

namespace App\Http\Controllers;

use App\Models\KategoriAnggaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KategoriAnggaranController extends Controller
{
    /**
     * Menampilkan halaman Master Kategori & Pagu Anggaran
     */
    public function index(Request $request): View
    {
        $kategoriList = KategoriAnggaran::latest()->get();

        return view('finance.kategori.index', compact('kategoriList'));
    }

    /**
     * Menyimpan kategori & pagu baru
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_anggarans,nama_kategori',
            'deskripsi' => 'nullable|string',
            'pagu_anggaran' => 'required|numeric|min:0',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori sudah digunakan.',
            'pagu_anggaran.required' => 'Pagu anggaran wajib diisi.',
            'pagu_anggaran.numeric' => 'Pagu anggaran harus berupa angka nominal.',
            'pagu_anggaran.min' => 'Pagu anggaran minimal bernilai 0.',
        ]);

        KategoriAnggaran::create($validated);

        return redirect()->route('finance.kategori.index')->with('success', 'Kategori anggaran berhasil ditambahkan.');
    }

    /**
     * Memperbarui data kategori & pagu
     */
    public function update(Request $request, int|string $id): RedirectResponse
    {
        $kategori = KategoriAnggaran::findOrFail($id);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_anggarans,nama_kategori,'.$kategori->id,
            'deskripsi' => 'nullable|string',
            'pagu_anggaran' => 'required|numeric|min:0',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori sudah digunakan.',
            'pagu_anggaran.required' => 'Pagu anggaran wajib diisi.',
            'pagu_anggaran.numeric' => 'Pagu anggaran harus berupa angka nominal.',
            'pagu_anggaran.min' => 'Pagu anggaran minimal bernilai 0.',
        ]);

        $kategori->update($validated);

        return redirect()->route('finance.kategori.index')->with('success', 'Kategori anggaran berhasil diperbarui.');
    }

    /**
     * Menghapus kategori anggaran
     */
    public function destroy(int|string $id): RedirectResponse
    {
        $kategori = KategoriAnggaran::findOrFail($id);
        $kategori->delete();

        return redirect()->route('finance.kategori.index')->with('success', 'Kategori anggaran berhasil dihapus.');
    }
}
