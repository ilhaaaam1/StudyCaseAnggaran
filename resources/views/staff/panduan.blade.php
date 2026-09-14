@extends('layouts.app')

@section('title', 'Panduan & SOP - SIRAB Kelompok-3')

@section('content')
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <span class="text-slate-800 font-medium">Panduan / SOP</span>
  </div>

  <div class="mb-8 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1">
      Pusat Bantuan
    </div>
    <h1 class="text-2xl font-bold text-slate-900">Panduan & Standar Operasional Prosedur</h1>
    <p class="text-sm text-slate-500 mt-1">
      Pelajari tata cara pengajuan Rencana Anggaran Biaya (RAB), syarat dokumen, dan alur persetujuan sistem.
    </p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Kolom Kiri: Accordion -->
    <div class="lg:col-span-2 space-y-4" x-data="{ active: 1 }">
      
      <!-- FAQ 1 -->
      <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm transition-all">
        <button @click="active = active === 1 ? null : 1" class="w-full px-6 py-4 flex items-center justify-between bg-slate-50 hover:bg-slate-100 transition-colors">
          <span class="font-bold text-slate-800 text-sm">Tata Cara Pengajuan RAB Baru</span>
          <svg :class="{'rotate-180': active === 1}" class="w-5 h-5 text-slate-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="active === 1" class="px-6 py-5 border-t border-slate-100 text-sm text-slate-600 leading-relaxed">
          <ol class="list-decimal list-inside space-y-2">
            <li>Buka menu <strong>Buat Pengajuan RAB</strong> melalui sidebar atau tombol cepat di Dashboard.</li>
            <li>Isi informasi dasar seperti Judul Pengajuan, Divisi (Otomatis terisi sesuai profil), dan Tujuan/Keterangan.</li>
            <li>Tambahkan baris rincian item anggaran (Nama Item, Volume, Harga Satuan). Estimasi total akan dihitung otomatis.</li>
            <li>Klik tombol <strong>Simpan sebagai Draft</strong> jika masih ingin diedit nanti, atau <strong>Ajukan ke Finance</strong> untuk segera mengirim permohonan.</li>
          </ol>
        </div>
      </div>

      <!-- FAQ 2 -->
      <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm transition-all">
        <button @click="active = active === 2 ? null : 2" class="w-full px-6 py-4 flex items-center justify-between bg-slate-50 hover:bg-slate-100 transition-colors">
          <span class="font-bold text-slate-800 text-sm">Syarat dan Ketentuan Lampiran</span>
          <svg :class="{'rotate-180': active === 2}" class="w-5 h-5 text-slate-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="active === 2" x-cloak class="px-6 py-5 border-t border-slate-100 text-sm text-slate-600 leading-relaxed">
          <p class="mb-3">Untuk beberapa kategori pengajuan (seperti pengadaan inventaris besar), tim Finance mungkin akan meminta dokumen pendukung tambahan.</p>
          <ul class="list-disc list-inside space-y-2 text-slate-600">
            <li>Lampiran Quotation/Penawaran Harga jika nilai di atas Rp 5.000.000.</li>
            <li>Format file yang diizinkan untuk bukti/lampiran adalah PDF, JPG, atau PNG.</li>
            <li>Maksimal ukuran file adalah 5MB per dokumen.</li>
          </ul>
        </div>
      </div>

      <!-- FAQ 3 -->
      <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm transition-all">
        <button @click="active = active === 3 ? null : 3" class="w-full px-6 py-4 flex items-center justify-between bg-slate-50 hover:bg-slate-100 transition-colors">
          <span class="font-bold text-slate-800 text-sm">Penjelasan Status Pengajuan</span>
          <svg :class="{'rotate-180': active === 3}" class="w-5 h-5 text-slate-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="active === 3" x-cloak class="px-6 py-5 border-t border-slate-100 text-sm text-slate-600 leading-relaxed">
          <div class="space-y-4">
            <div class="flex items-start gap-3">
              <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded text-xs font-semibold shrink-0">Menunggu Finance</span>
              <p class="text-sm">Pengajuan sedang direview pada tahap 1 oleh tim Finance untuk verifikasi nilai pagu dan rincian item.</p>
            </div>
            <div class="flex items-start gap-3">
              <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold shrink-0">Menunggu Pimpinan</span>
              <p class="text-sm">Lolos tahap 1, sedang menunggu persetujuan akhir (tanda tangan) dari pihak Pimpinan.</p>
            </div>
            <div class="flex items-start gap-3">
              <span class="px-2 py-1 bg-slate-100 text-slate-800 rounded text-xs font-semibold shrink-0">Revisi</span>
              <p class="text-sm">Terdapat kesalahan. Anda harus membuka pengajuan, membaca catatan dari reviewer, dan memperbaikinya.</p>
            </div>
            <div class="flex items-start gap-3">
              <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded text-xs font-semibold shrink-0">Proses Pencairan</span>
              <p class="text-sm">Pengajuan telah disetujui penuh! Menunggu tim Finance mentransfer dana dan mengunggah bukti bayar.</p>
            </div>
            <div class="flex items-start gap-3">
              <span class="px-2 py-1 bg-emerald-100 text-emerald-800 rounded text-xs font-semibold shrink-0">Selesai</span>
              <p class="text-sm">Dana telah dicairkan. Proses RAB selesai.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Kolom Kanan: Card Info Cepat -->
    <div class="space-y-6">
      <div class="bg-indigo-600 text-white rounded-2xl p-6 shadow-md relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white/10 blur-2xl"></div>
        
        <h3 class="font-bold text-lg mb-2 relative z-10">Butuh Bantuan?</h3>
        <p class="text-sm text-indigo-100 mb-6 relative z-10">Jika Anda mengalami kendala teknis atau memiliki pertanyaan terkait batas anggaran pagu divisi Anda, silakan hubungi tim IT atau Finance.</p>
        
        <div class="space-y-3 relative z-10 text-sm">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center shrink-0">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <span>finance@sirab.id</span>
          </div>
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center shrink-0">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            </div>
            <span>(021) 555-0123</span>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
