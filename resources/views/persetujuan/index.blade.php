@extends('layouts.app')

@section('title', 'Persetujuan - SIRAB')

@section('main_class', 'max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6')

@section('content')
  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6">
    SIRAB / <span class="text-slate-800 font-medium">Persetujuan</span>
  </div>

  <!-- Page Header -->
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Antrean Persetujuan</h1>
    <p class="text-sm text-slate-500 mt-1">
      Pengajuan anggaran yang menunggu verifikasi dan persetujuan
    </p>
  </div>

  @if(session('success'))
    <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
      <div class="flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('success') }}</span>
      </div>
    </div>
  @endif

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-amber-50/50 border border-amber-100 rounded-lg p-4 shadow-sm">
      <div class="text-xs font-semibold text-amber-600 mb-1">
        Menunggu Review
      </div>
      <div class="text-2xl font-bold text-amber-700 font-mono-num">{{ $pendingCount }}</div>
    </div>
    <div class="bg-emerald-50/50 border border-emerald-100 rounded-lg p-4 shadow-sm">
      <div class="text-xs font-semibold text-emerald-600 mb-1">
        Disetujui
      </div>
      <div class="text-2xl font-bold text-emerald-700 font-mono-num">{{ $approvedCount }}</div>
    </div>
    <div class="bg-rose-50/50 border border-rose-100 rounded-lg p-4 shadow-sm">
      <div class="text-xs font-semibold text-rose-600 mb-1">
        Ditolak / Revisi
      </div>
      <div class="text-2xl font-bold text-rose-700 font-mono-num">{{ $rejectedCount }}</div>
    </div>
  </div>

  <!-- Items Queue Section -->
  <div class="space-y-6 mb-10">
    @forelse($pendingRabs as $rab)
      <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
        <!-- Header Card -->
        <div class="flex flex-col sm:flex-row justify-between items-start mb-4 gap-4">
          <div>
            <div class="flex flex-wrap items-center gap-3 mb-2">
              <span class="text-xs font-mono font-medium text-slate-600">
                {{ $rab->code }}
              </span>
              <span class="text-[10px] font-semibold {{ $rab->priority->badgeClasses() }} px-2 py-0.5 rounded">
                Urgensi: {{ $rab->priority->label() }}
              </span>
              <span class="inline-flex items-center gap-1 text-[10px] font-semibold {{ $rab->status->badgeClasses() }} px-2.5 py-0.5 rounded">
                <span class="w-1.5 h-1.5 rounded-full {{ $rab->status->dotClasses() }}"></span>
                {{ $rab->status->label() }}
              </span>
            </div>
            <h2 class="text-base font-semibold text-slate-900">
              {{ $rab->title }}
            </h2>
            <div class="text-xs text-slate-400 mt-1">
              Pengaju: {{ $rab->user->name ?? '-' }} ({{ $rab->division }}) · Diajukan: {{ $rab->created_at->format('Y-m-d') }}
            </div>
            @if($rab->justification)
              <p class="text-xs text-slate-600 mt-2 italic bg-slate-50 p-2.5 rounded-md border border-slate-100">
                &ldquo;{{ $rab->justification }}&rdquo;
              </p>
            @endif
          </div>
          <div class="text-xl font-bold text-slate-800 font-mono-num">
            Rp {{ number_format((float)$rab->total_amount, 0, ',', '.') }}
          </div>
        </div>

        <form method="POST" action="{{ route('persetujuan.update', $rab) }}">
          @csrf
          <div class="border-t border-slate-100 pt-5 mt-2 grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Ringkasan Item -->
            <div>
              <h3 class="text-[10px] font-bold text-slate-500 tracking-wider uppercase mb-3">
                Ringkasan Item
              </h3>
              <div class="space-y-2 text-xs">
                @forelse($rab->items as $item)
                  <div class="flex justify-between text-slate-600">
                    <span>{{ $item->description }} ({{ $item->quantity }} {{ $item->unit }})</span>
                    <span class="font-mono-num font-medium text-slate-700">Rp {{ number_format((float)$item->total_price, 0, ',', '.') }}</span>
                  </div>
                @empty
                  <div class="text-slate-400 italic">Tidak ada rincian item.</div>
                @endforelse
              </div>

              @if($rab->attachments->isNotEmpty())
                <div class="mt-4 pt-3 border-t border-slate-100">
                  <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Lampiran Dokumen</h4>
                  <div class="space-y-1.5">
                    @foreach($rab->attachments as $file)
                      <div class="flex items-center gap-2 text-xs text-slate-600">
                        <span>📎</span>
                        <span class="truncate font-medium">{{ $file->file_name }}</span>
                        @if($file->file_size)
                          <span class="text-[10px] text-slate-400">({{ $file->file_size }})</span>
                        @endif
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif
            </div>

            <!-- Catatan Review -->
            <div>
              <h3 class="text-[10px] font-bold text-slate-500 tracking-wider uppercase mb-3">
                Catatan Persetujuan / Evaluasi
              </h3>
              <textarea
                name="admin_note"
                rows="4"
                placeholder="Tambahkan catatan persetujuan atau alasan revisi/penolakan..."
                class="w-full border border-slate-200 rounded-lg p-3 text-xs text-slate-700 placeholder-slate-400 resize-none bg-slate-50/50 focus:bg-white transition-colors"
              >{{ old('admin_note', $rab->admin_note) }}</textarea>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="border-t border-slate-100 pt-5 mt-5 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
              <button
                type="submit"
                name="action"
                value="approve"
                class="flex-1 sm:flex-none bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold px-4 py-2.5 rounded-lg transition-colors flex items-center justify-center gap-1.5 shadow-sm"
              >
                ✓ Setujui Pengajuan
              </button>
              <button
                type="submit"
                name="action"
                value="reject"
                class="flex-1 sm:flex-none bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold px-4 py-2.5 rounded-lg transition-colors flex items-center justify-center gap-1.5 shadow-sm"
              >
                ✕ Tolak
              </button>
              <button
                type="submit"
                name="action"
                value="revision"
                class="flex-1 sm:flex-none bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 text-xs font-semibold px-4 py-2.5 rounded-lg transition-colors flex items-center justify-center gap-1.5"
              >
                ↩ Minta Revisi
              </button>
            </div>
          </div>
        </form>
      </div>
    @empty
      <div class="bg-white border border-slate-200 rounded-xl p-12 text-center text-slate-400 shadow-sm">
        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-sm font-medium text-slate-600">Semua pengajuan telah ditinjau</p>
        <p class="text-xs text-slate-400 mt-1">Tidak ada dokumen yang sedang menunggu persetujuan saat ini.</p>
      </div>
    @endforelse
  </div>

  <!-- Riwayat Persetujuan Terakhir -->
  <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-8">
    <div class="p-6 border-b border-slate-100">
      <h2 class="font-semibold text-slate-900">Riwayat Persetujuan Terakhir</h2>
    </div>
    <div class="px-6 py-2">
      <div class="divide-y divide-slate-100">
        @forelse($historyRabs as $history)
          <div class="py-4 flex flex-col sm:flex-row sm:items-center gap-4 hover:bg-slate-50 -mx-6 px-6 transition-colors">
            <div class="w-24 shrink-0">
              <span class="inline-flex items-center gap-1.5 text-[10px] font-semibold {{ $history->status->badgeClasses() }} px-2.5 py-1 rounded-full border border-slate-100">
                <span class="w-1.5 h-1.5 rounded-full {{ $history->status->dotClasses() }}"></span>
                {{ $history->status->label() }}
              </span>
            </div>
            <div class="flex-1 min-w-0 flex items-center gap-3 text-sm">
              <span class="font-mono text-slate-500 text-xs">{{ $history->code }}</span>
              <span class="text-slate-800 font-medium truncate">{{ $history->title }}</span>
            </div>
            <div class="flex items-center justify-between sm:justify-end gap-6 sm:w-56 shrink-0">
              <span class="font-mono-num text-sm text-slate-700 font-medium">
                Rp {{ number_format((float)$history->total_amount, 0, ',', '.') }}
              </span>
              <span class="text-xs text-slate-400 font-mono">
                {{ $history->approved_at?->format('Y-m-d') ?? $history->updated_at->format('Y-m-d') }}
              </span>
            </div>
          </div>
        @empty
          <div class="py-6 text-center text-xs text-slate-400">
            Belum ada riwayat persetujuan.
          </div>
        @endforelse
      </div>
    </div>
  </div>
@endsection
