<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRabRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $kategoriSekolah = [
            'Belanja Barang Operasional & ATK',
            'Kegiatan Kesiswaan & Lomba',
            'Pemeliharaan Sarana & Prasarana',
            'Pengembangan Perpustakaan & Literasi',
            'Peningkatan Kompetensi Guru (SDM)',
            'Langganan Daya & Jasa',
            'Belanja Modal / Alat Elektronik',
            // Kompatibilitas kategori legacy
            'Operasional Rutin',
            'Pengadaan Barang/Aset',
            'Pemeliharaan & Perbaikan',
            'Kegiatan / Acara',
        ];

        return [
            'id_divisi' => ['required', 'integer', 'exists:divisi,id_divisi'],
            'judul_pengajuan' => ['required', 'string', 'max:255'],
            'tahun_ajaran_semester' => ['required', 'string', 'max:100'],
            'tahap_bos' => ['required', 'string', 'max:100'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'periode_penggunaan' => ['nullable', 'string', 'max:255'],
            'kategori_anggaran' => ['required', 'string', 'in:'.implode(',', $kategoriSekolah)],
            'latar_belakang' => ['required', 'string'],

            // Validasi Rincian Item (Array)
            'items' => ['required', 'array', 'min:1'],
            'items.*.uraian_barang' => ['required', 'string', 'max:255'],
            'items.*.satuan' => ['required', 'string', 'max:50'],
            'items.*.volume' => ['required', 'numeric', 'min:0.01'],
            'items.*.harga_satuan' => ['required', 'numeric', 'min:0'],

            // Validasi Dokumen Pendukung (Upload File, max 5MB = 5120 KB)
            'dokumen_pendukung' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    /**
     * Custom attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'id_divisi' => 'Unit Kerja Sekolah',
            'judul_pengajuan' => 'Judul Pengajuan Kegiatan',
            'tahun_ajaran_semester' => 'Tahun Ajaran & Semester',
            'tahap_bos' => 'Tahap Penyaluran BOS',
            'tanggal_mulai' => 'Tanggal Mulai Kegiatan',
            'tanggal_selesai' => 'Tanggal Selesai Kegiatan',
            'periode_penggunaan' => 'Periode Penggunaan',
            'kategori_anggaran' => 'Kategori Pos Anggaran',
            'latar_belakang' => 'Latar Belakang & Urgensi',
            'items' => 'Rincian Item Belanja',
            'items.*.uraian_barang' => 'Uraian Barang / Kegiatan',
            'items.*.satuan' => 'Satuan',
            'items.*.volume' => 'Volume',
            'items.*.harga_satuan' => 'Harga Satuan',
            'dokumen_pendukung' => 'Dokumen Pendukung',
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id_divisi.required' => 'Unit Kerja Sekolah wajib dipilih.',
            'id_divisi.exists' => 'Unit Kerja Sekolah yang dipilih tidak valid.',
            'judul_pengajuan.required' => 'Judul pengajuan kegiatan wajib diisi.',
            'tahun_ajaran_semester.required' => 'Tahun ajaran dan semester wajib dipilih.',
            'tahap_bos.required' => 'Tahap penyaluran dana BOS wajib dipilih.',
            'tanggal_mulai.required' => 'Tanggal mulai pelaksanaan kegiatan wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai pelaksanaan kegiatan wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai kegiatan tidak boleh lebih awal dari tanggal mulai kegiatan.',
            'kategori_anggaran.required' => 'Kategori pos anggaran wajib dipilih.',
            'kategori_anggaran.in' => 'Kategori pos anggaran tidak sesuai standar RKAS / BOS.',
            'items.required' => 'Setidaknya harus ada satu rincian item anggaran.',
            'items.min' => 'Setidaknya harus ada satu rincian item anggaran.',
            'items.*.volume.min' => 'Volume item harus lebih besar dari 0.',
            'items.*.harga_satuan.min' => 'Harga satuan tidak boleh negatif.',
            'dokumen_pendukung.mimes' => 'Format file dokumen pendukung harus berupa PDF, JPG, JPEG, atau PNG.',
            'dokumen_pendukung.max' => 'Ukuran file dokumen pendukung maksimal 5MB (5120 KB).',
        ];
    }
}
