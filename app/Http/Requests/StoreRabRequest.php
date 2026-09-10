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
        return [
            'id_divisi' => ['required', 'integer', 'exists:divisi,id_divisi'],
            'judul_pengajuan' => ['required', 'string', 'max:255'],
            'periode_penggunaan' => ['required', 'string', 'max:50'],
            'prioritas' => ['required', 'in:Rendah,Sedang,Tinggi'],
            'latar_belakang' => ['required', 'string'],

            // Validasi Rincian Item (Array)
            'items' => ['required', 'array', 'min:1'],
            'items.*.uraian_barang' => ['required', 'string', 'max:255'],
            'items.*.satuan' => ['required', 'string', 'max:50'],
            'items.*.volume' => ['required', 'integer', 'min:1'],
            'items.*.harga_satuan' => ['required', 'numeric', 'min:0'],

            // Validasi Dokumen Pendukung (Upload File, max 5MB = 5120 KB)
            'dokumen' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
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
            'id_divisi' => 'Divisi Pemohon',
            'judul_pengajuan' => 'Judul Pengajuan',
            'periode_penggunaan' => 'Periode Penggunaan',
            'prioritas' => 'Prioritas',
            'latar_belakang' => 'Latar Belakang',
            'items' => 'Rincian Item',
            'items.*.uraian_barang' => 'Uraian Barang',
            'items.*.satuan' => 'Satuan',
            'items.*.volume' => 'Volume',
            'items.*.harga_satuan' => 'Harga Satuan',
            'dokumen' => 'Dokumen Pendukung',
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
            'items.required' => 'Setidaknya harus ada satu rincian item anggaran.',
            'items.min' => 'Setidaknya harus ada satu rincian item anggaran.',
            'dokumen.mimes' => 'Format file dokumen pendukung harus berupa PDF, JPG, JPEG, atau PNG.',
            'dokumen.max' => 'Ukuran file dokumen pendukung maksimal 5MB (5120 KB).',
        ];
    }
}
