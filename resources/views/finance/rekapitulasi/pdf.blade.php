<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cetak Rekapitulasi Laporan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .instansi { font-size: 18px; font-weight: bold; margin-bottom: 5px; margin-top: 0px; }
        .alamat { font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    @php
        $logoPath = \App\Models\Setting::getSetting('app_logo');
        $appName = \App\Models\Setting::getSetting('app_name', 'SD Negeri Sidokare 3');
        
        // PRESENTASI: Konversi Logo ke Base64 (Untuk Kompatibilitas DomPDF)
        // Library PDF seperti DomPDF sering gagal merender path absolut URL (http://...), 
        // sehingga file gambar harus dibaca langsung dari server dan dikonversi ke format string base64.
        if ($logoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath)) {
            $path = storage_path('app/public/' . $logoPath);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = @file_get_contents($path);
            $logoUrl = $data ? 'data:image/' . $type . ';base64,' . base64_encode($data) : '';
        } else {
            $path = public_path('images/logo-sdn3.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = @file_get_contents($path);
            $logoUrl = $data ? 'data:image/' . $type . ';base64,' . base64_encode($data) : '';
        }
    @endphp

    {{-- PRESENTASI: Menggunakan HTML Table klasik agar layout Kop Surat stabil saat dirender oleh DomPDF --}}
    <table width="100%" style="border-collapse: collapse; border: none; margin-top: 0;">
        <tr>
            <td style="width: 15%; text-align: center; vertical-align: middle; border: none; padding: 0;">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo" style="width: 80px;">
                @endif
            </td>
            <td style="width: 85%; text-align: center; vertical-align: middle; border: none; padding: 0;">
                <div class="instansi">{{ strtoupper($appName) }}</div>
                <div class="alamat">
                    Alamat: Cangkring, Sidokare, Kec. Sidoarjo, Kabupaten Sidoarjo<br>
                    Telp: (031) 8965532 | Email: {{ \App\Models\Setting::getSetting('admin_email', 'admin@sekolah.sch.id') }}
                </div>
            </td>
        </tr>
    </table>
    <hr style="border: 0; border-top: 3px solid black; margin-top: 10px; margin-bottom: 20px;">

    <h3 style="text-align: center;">REKAPITULASI LAPORAN PENGAJUAN ANGGARAN</h3>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Diajukan</th>
                <th>No RAB</th>
                <th>Pemohon & Bidang / Bagian</th>
                <th>Kategori</th>
                <th style="text-align: right;">Total Anggaran</th>
                <th>Status Akhir</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse($rekapList as $index => $item)
                @php $grandTotal += (float) $item->estimasi_total; @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y') : '-' }}</td>
                    <td>{{ $item->no_rab }}</td>
                    <td>
                        <strong>{{ $item->pengguna->nama_lengkap ?? '-' }}</strong><br>
                        <span style="font-size: 10px; color: #555;">{{ $item->divisi->nama_divisi ?? '-' }}</span>
                    </td>
                    <td>{{ $item->kategori_anggaran ?? '-' }}</td>
                    <td style="text-align: right;">Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}</td>
                    <td>{{ $item->status->value ?? $item->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        Tidak ada data rekapitulasi pada rentang ini.
                    </td>
                </tr>
            @endforelse
            <tr>
                <th colspan="5" style="text-align: right;">TOTAL KESELURUHAN</th>
                <th style="text-align: right;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
                <th></th>
            </tr>
        </tbody>
    </table>
</body>
</html>