<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BarangController extends Controller
{
    /**
     * Menampilkan daftar seluruh barang inventaris (Tahap 5: Tampil).
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');

        $query = Barang::query();

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $barangs = $query->latest()->paginate(10)->withQueryString();

        return view('barang.index', compact('barangs', 'search'));
    }

    /**
     * Menampilkan formulir tambah barang baru (Tahap 1: Form).
     */
    public function create(): View
    {
        return view('barang.create');
    }

    /**
     * Memvalidasi dan menyimpan barang baru beserta berkas gambar (Tahap 2, 3, 4).
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi server (Tahap 2)
        $request->validate([
            'kategori' => 'required|string|max:100',
            'nama' => 'required|string|max:150',
            'jumlah' => 'required|integer|min:0',
            'kondisi' => 'required|in:baik,rusak,hilang',
            'keterangan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Ambil data selain file gambar
        $data = $request->except('gambar');

        // 3. Simpan file ke disk public & siapkan path untuk database (Tahap 3 & 4)
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('barang', $filename, 'public');
            $data['gambar'] = 'storage/barang/'.$filename;
        }

        Barang::create($data);

        return redirect()->route('barang.index')
            ->with('success', 'Data barang inventaris berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail spesifik satu barang (Tahap 5: Tampil Gambar Detail).
     */
    public function show(Barang $barang): View
    {
        return view('barang.show', compact('barang'));
    }

    /**
     * Menampilkan formulir edit barang dengan pratinjau foto lama.
     */
    public function edit(Barang $barang): View
    {
        return view('barang.edit', compact('barang'));
    }

    /**
     * Memperbarui data barang dan merawat file storage (hapus file lama jika diganti/dihapus).
     */
    public function update(Request $request, Barang $barang): RedirectResponse
    {
        // 1. Validasi data
        $request->validate([
            'kategori' => 'required|string|max:100',
            'nama' => 'required|string|max:150',
            'jumlah' => 'required|integer|min:0',
            'kondisi' => 'required|in:baik,rusak,hilang',
            'keterangan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:100',
        ]);

        $data = $request->except(['gambar', 'hapus_gambar']);

        // 2. Jika opsi hapus gambar dicentang dan tidak upload gambar baru
        if ($request->boolean('hapus_gambar') && ! $request->hasFile('gambar')) {
            if ($barang->gambar) {
                Storage::disk('public')->delete('barang/'.basename($barang->gambar));
            }
            $data['gambar'] = null;
        }

        // 3. Jika pengguna mengunggah gambar pengganti baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama dari disk agar tidak menjadi file sampah/yatim
            if ($barang->gambar) {
                Storage::disk('public')->delete('barang/'.basename($barang->gambar));
            }

            $file = $request->file('gambar');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('barang', $filename, 'public');
            $data['gambar'] = 'storage/barang/'.$filename;
        }

        $barang->update($data);

        return redirect()->route('barang.index')
            ->with('success', 'Data barang inventaris berhasil diperbarui.');
    }

    /**
     * Menghapus barang dan membersihkan file fisiknya dari storage.
     */
    public function destroy(Barang $barang): RedirectResponse
    {
        // Hapus file fisik dari disk sebelum menghapus record database
        if ($barang->gambar) {
            Storage::disk('public')->delete('barang/'.basename($barang->gambar));
        }

        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Barang beserta file gambarnya berhasil dihapus.');
    }
}
