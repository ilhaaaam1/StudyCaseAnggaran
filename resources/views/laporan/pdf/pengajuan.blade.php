<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Pengajuan Rencana Anggaran Biaya (RAB)</title>
    <style>
        @page {
            size: a4 landscape;
            margin: 12mm 15mm 15mm 15mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif, Helvetica, Arial;
            font-size: 10px;
            color: #1f2937;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* Kop Surat Instansi */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            margin-bottom: 0px;
        }
        .kop-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .kop-instansi {
            font-size: 15px;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .kop-subinstansi {
            font-size: 11px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 4px;
        }
        .kop-alamat {
            font-size: 9px;
            color: #4b5563;
            line-height: 1.3;
        }
        .kop-divider {
            border-top: 2px solid #111827;
            border-bottom: 1px solid #111827;
            height: 2px;
            margin: 8px 0 14px 0;
        }

        /* Judul Laporan */
        .judul-laporan {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 4px 0;
            color: #111827;
        }
        .subjudul-laporan {
            text-align: center;
            font-size: 9.5px;
            color: #4b5563;
            margin-bottom: 12px;
        }

        /* Metadata Cetak */
        .meta-table {
            width: 100%;
            margin-bottom: 8px;
            border-collapse: collapse;
            font-size: 9px;
        }
        .meta-table td {
            border: none;
            padding: 2px 0;
            color: #374151;
        }

        /* Tabel Data Pengajuan */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            font-size: 9.5px;
        }
        .data-table th, .data-table td {
            border: 1px solid #9ca3af;
            padding: 6px 7px;
            vertical-align: middle;
        }
        .data-table th {
            background-color: #f3f4f6;
            color: #111827;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.3px;
            text-align: center;
        }
        .data-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-mono { font-family: monospace; font-size: 9px; }

        /* Status Badge */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .badge-warning { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .badge-info { background-color: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
        .badge-default { background-color: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }

        /* Lembar Pengesahan / Tanda Tangan */
        .ttd-section {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }
        .ttd-table td {
            border: none;
            padding: 0;
            vertical-align: top;
            text-align: center;
            font-size: 9.5px;
        }
        .ttd-box {
            height: 60px;
        }
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }
        .ttd-nip {
            font-size: 8.5px;
            color: #4b5563;
        }
    </style>
</head>
<body>
    @php
        $logoPath = \App\Models\Setting::getSetting('app_logo');
        $appName = \App\Models\Setting::getSetting('app_name', 'SD Negeri Sidokare 3');
        $instansiParent = \App\Models\Setting::getSetting('instansi_parent', 'PEMERINTAH KABUPATEN SIDOARJO / DINAS PENDIDIKAN');
        
        // Membaca file logo dan convert ke Base64 agar DomPDF merender gambar secara optimal
        $logoUrl = '';
        if ($logoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath)) {
            $path = storage_path('app/public/' . $logoPath);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = @file_get_contents($path);
            $logoUrl = $data ? 'data:image/' . $type . ';base64,' . base64_encode($data) : '';
        } else {
            $path = public_path('images/logo-sdn3.png');
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = @file_get_contents($path);
                $logoUrl = $data ? 'data:image/' . $type . ';base64,' . base64_encode($data) : '';
            }
        }

        $totalKeseluruhan = 0;
    @endphp

    {{-- KOP SURAT FORMAL --}}
    <table class="kop-table">
        <tr>
            <td style="width: 12%; text-align: center;">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo" style="width: 65px; height: auto;">
                @endif
            </td>
            <td style="width: 88%; text-align: center;">
                <div class="kop-subinstansi">{{ strtoupper($instansiParent) }}</div>
                <div class="kop-instansi">{{ strtoupper($appName) }}</div>
                <div class="kop-alamat">
                    Alamat: Cangkring, Sidokare, Kec. Sidoarjo, Kabupaten Sidoarjo, Jawa Timur 61214<br>
                    Website: {{ config('app.url', 'https://sirab.sdnsidokare3.sch.id') }} | Email: {{ \App\Models\Setting::getSetting('admin_email', 'info@sdnsidokare3.sch.id') }} | Telp: (031) 8965532
                </div>
            </td>
        </tr>
    </table>
    <div class="kop-divider"></div>

    {{-- JUDUL & INFORMASI DOKUMEN --}}
    <div class="judul-laporan">Laporan Pengajuan Rencana Anggaran Biaya (RAB)</div>
    <div class="subjudul-laporan">Sistem Informasi Pengajuan dan Persetujuan Anggaran Sekolah (SIRAB)</div>

    <table class="meta-table">
        <tr>
            <td style="width: 50%;">
                <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} (Pukul {{ date('H:i') }} WIB)<br>
                <strong>Dicetak Oleh:</strong> {{ auth()->user()->nama_lengkap ?? auth()->user()->name ?? 'Administrator SIRAB' }} ({{ ucfirst(auth()->user()->role ?? 'Staff') }})
            </td>
            <td style="width: 50%; text-align: right;">
                <strong>Format Dokumen:</strong> Lembar Kerja A4 Landscape<br>
                <strong>Jumlah Berkas:</strong> {{ $pengajuans->count() }} Pengajuan Anggaran
            </td>
        </tr>
    </table>

    {{-- TABEL DATA PENGAJUAN RAB --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 14%;">No. Dokumen RAB</th>
                <th style="width: 9%;">Tgl Pengajuan</th>
                <th style="width: 16%;">Pemohon & Unit Kerja</th>
                <th style="width: 20%;">Uraian Judul & Kategori</th>
                <th style="width: 9%;">Rincian</th>
                <th style="width: 14%;">Total Anggaran</th>
                <th style="width: 15%;">Status Pengajuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuans as $p)
                @php
                    $totalKeseluruhan += (float) $p->estimasi_total;
                    $statusEnum = $p->status;
                    $statusVal = $statusEnum instanceof \App\Enums\StatusPengajuan ? $statusEnum->value : ($statusEnum->value ?? (string) $statusEnum);
                    $statusLabel = $statusEnum instanceof \App\Enums\StatusPengajuan ? $statusEnum->label() : ucfirst(str_replace('_', ' ', $statusVal));

                    // Tentukan style badge status
                    $badgeClass = match($statusVal) {
                        'disetujui' => 'badge-success',
                        'ditolak' => 'badge-danger',
                        'revisi' => 'badge-warning',
                        'menunggu_finance', 'menunggu_pimpinan' => 'badge-info',
                        default => 'badge-default',
                    };
                @endphp
                <tr>
                    <td class="text-center font-mono">{{ $loop->iteration }}</td>
                    <td class="font-mono">
                        <strong>{{ $p->no_rab ?? '-' }}</strong>
                    </td>
                    <td class="text-center">
                        {{ $p->tanggal_pengajuan ? $p->tanggal_pengajuan->format('d/m/Y') : ($p->created_at ? $p->created_at->format('d/m/Y') : '-') }}
                    </td>
                    <td>
                        <strong>{{ $p->user->nama_lengkap ?? $p->user->name ?? $p->pengguna->nama_lengkap ?? '-' }}</strong><br>
                        <span style="color: #4b5563; font-size: 8.5px;">{{ $p->divisi->nama_divisi ?? 'Divisi Umum' }}</span>
                    </td>
                    <td>
                        <span style="font-weight: bold; color: #111827;">{{ $p->judul_pengajuan ?? '-' }}</span><br>
                        <span style="color: #6b7280; font-size: 8.5px;">Kategori: {{ $p->kategori_anggaran ?: 'BOS Reguler' }}</span>
                    </td>
                    <td class="text-center">
                        {{ $p->items ? $p->items->count() : ($p->rincianItem ? $p->rincianItem->count() : 0) }} Item
                    </td>
                    <td class="text-right font-mono" style="font-weight: bold; color: #111827;">
                        Rp {{ number_format((float) $p->estimasi_total, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 18px; color: #6b7280;">
                        Tidak ada data pengajuan rencana anggaran biaya yang tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($pengajuans->isNotEmpty())
            <tfoot>
                <tr style="background-color: #e5e7eb; font-weight: bold;">
                    <td colspan="6" class="text-right" style="font-size: 10px; text-transform: uppercase;">
                        TOTAL AKUMULASI ANGGARAN:
                    </td>
                    <td class="text-right font-mono" style="font-size: 10px; color: #1e3a8a;">
                        Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        @endif
    </table>

    {{-- LEMBAR PENGESAHAN / TANDA TANGAN (Sesuai Poin 5 Slide 16) --}}
    <div class="ttd-section">
        <table class="ttd-table">
            <tr>
                <td style="width: 33%;">
                    Mengetahui,<br>
                    <strong>Kepala Sekolah / Kuasa Pengguna Anggaran</strong>
                    <div class="ttd-box"></div>
                    <div class="ttd-nama">Dra. Hj. Nurul Hidayati, M.Pd</div>
                    <div class="ttd-nip">NIP. 19680512 199303 2 004</div>
                </td>
                <td style="width: 34%;">
                    Diverifikasi Oleh,<br>
                    <strong>Bendahara Sekolah / Reviewer Keuangan</strong>
                    <div class="ttd-box"></div>
                    <div class="ttd-nama">Siti Rahmawati, S.E.</div>
                    <div class="ttd-nip">NIP. 19820719 200801 2 011</div>
                </td>
                <td style="width: 33%;">
                    Sidoarjo, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                    <strong>Pembuat Laporan / Tim Penyusun RAB</strong>
                    <div class="ttd-box"></div>
                    <div class="ttd-nama">{{ auth()->user()->nama_lengkap ?? auth()->user()->name ?? 'Staf Administrasi' }}</div>
                    <div class="ttd-nip">NIP. {{ auth()->user()->nip ?? '19900315 201502 1 002' }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Script PHP DomPDF untuk Nomor Halaman Otomatis (Materi Lanjutan Slide 15) --}}
    <script type="text/php">
        if (isset($pdf)) {
            $x = 40;
            $y = 565;
            $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}  |  Dicetak melalui Sistem Informasi SIRAB pada " . date('d/m/Y H:i:s');
            $font = $fontMetrics->get_font("DejaVu Sans, sans-serif", "normal");
            $size = 8;
            $color = array(0.4, 0.4, 0.4);
            $pdf->page_text($x, $y, $text, $font, $size, $color);
        }
    </script>
</body>
</html>
