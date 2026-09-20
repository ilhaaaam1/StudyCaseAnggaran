<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePengajuanRabRequest extends FormRequest
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
            'id_pengguna' => ['required', 'integer', 'exists:pengguna,id_pengguna'],
            'id_divisi' => ['required', 'integer', 'exists:divisi,id_divisi'],
            'no_rab' => ['required', 'string', 'max:50', 'unique:pengajuan_rab,no_rab'],
            'judul_pengajuan' => ['required', 'string', 'max:255'],
            'periode_penggunaan' => ['required', 'string', 'max:50'],
            'prioritas' => ['required', 'in:rendah,sedang,tinggi'],
            'latar_belakang' => ['required', 'string'],
            'status' => ['nullable', 'in:draft,diajukan'],

            // Validasi Rincian Item (Nested Array)
            'rincian' => ['required', 'array', 'min:1'],
            'rincian.*.uraian_barang' => ['required', 'string', 'max:255'],
            'rincian.*.satuan' => ['required', 'string', 'max:20'],
            'rincian.*.volume' => ['required', 'integer', 'min:1'],
            'rincian.*.harga_satuan' => ['required', 'numeric', 'min:0'],

            // Validasi Dokumen Pendukung (File Upload)
            'dokumen.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    /**
     * Custom attributes name for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'id_pengguna' => 'Pengguna Pemohon',
            'id_divisi' => 'Divisi / Unit Kerja',
            'no_rab' => 'Nomor RAB',
            'judul_pengajuan' => 'Judul Pengajuan',
            'periode_penggunaan' => 'Periode Penggunaan',
            'prioritas' => 'Prioritas',
            'latar_belakang' => 'Latar Belakang & Justifikasi',
            'status' => 'Status Pengajuan',
            'rincian' => 'Rincian Item',
            'rincian.*.uraian_barang' => 'Uraian Barang/Kegiatan',
            'rincian.*.satuan' => 'Satuan',
            'rincian.*.volume' => 'Volume',
            'rincian.*.harga_satuan' => 'Harga Satuan',
            'dokumen.*' => 'Dokumen Lampiran',
        ];
    }
}
