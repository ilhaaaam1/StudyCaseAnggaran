@extends('layouts.app')

@section('title', 'Statistik Anggaran - SIRAB Kelompok-3')

@section('content')
  <!-- Header & Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <span class="text-slate-800 font-medium">Statistik Anggaran</span>
  </div>

  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
        Laporan Eksekutif
      </div>
      <h1 class="text-2xl font-bold text-slate-900">Statistik Anggaran</h1>
      <p class="text-sm text-slate-500 mt-1">
        Ringkasan penyerapan dan alokasi anggaran perusahaan.
      </p>
    </div>
    
    <!-- Filter Waktu Dropdown -->
    <form method="GET" action="{{ route('pimpinan.statistik.index') }}" class="shrink-0 flex items-center gap-2" id="filterForm">
      <label for="filter_waktu" class="text-xs font-semibold text-slate-600">Filter Waktu:</label>
      <select name="filter_waktu" id="filter_waktu" onchange="document.getElementById('filterForm').submit()" class="border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2">
        <option value="bulan_ini" {{ $filterWaktu == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
        <option value="kuartal_ini" {{ $filterWaktu == 'kuartal_ini' ? 'selected' : '' }}>Kuartal Ini</option>
        <option value="tahun_ini" {{ $filterWaktu == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
      </select>
    </form>
  </div>

  <!-- Summary Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-emerald-50/70 border border-emerald-200 rounded-xl p-5 shadow-sm">
      <div class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider mb-1">TOTAL ANGGARAN DISETUJUI</div>
      <div class="text-2xl font-bold text-emerald-700 font-mono">Rp {{ number_format($totalDisetujui ?? 0, 0, ',', '.') }}</div>
      <div class="text-xs text-emerald-600 mt-1">Yang sudah ACC final</div>
    </div>

    <div class="bg-blue-50/70 border border-blue-200 rounded-xl p-5 shadow-sm">
      <div class="text-[11px] font-bold text-blue-800 uppercase tracking-wider mb-1">TOTAL DANA DICAIRKAN</div>
      <div class="text-2xl font-bold text-blue-700 font-mono">Rp {{ number_format($totalDicairkan ?? 0, 0, ',', '.') }}</div>
      <div class="text-xs text-blue-600 mt-1">Telah dicairkan Finance</div>
    </div>

    <div class="bg-amber-50/70 border border-amber-200 rounded-xl p-5 shadow-sm">
      <div class="text-[11px] font-bold text-amber-800 uppercase tracking-wider mb-1">TOTAL PENGAJUAN MENUNGGU</div>
      <div class="text-2xl font-bold text-amber-700 font-mono">Rp {{ number_format($totalMenunggu ?? 0, 0, ',', '.') }}</div>
      <div class="text-xs text-amber-600 mt-1">Menunggu persetujuan pimpinan</div>
    </div>

    <div class="bg-rose-50/70 border border-rose-200 rounded-xl p-5 shadow-sm">
      <div class="text-[11px] font-bold text-rose-800 uppercase tracking-wider mb-1">TOTAL ANGGARAN DITOLAK</div>
      <div class="text-2xl font-bold text-rose-700 font-mono">Rp {{ number_format($totalDitolak ?? 0, 0, ',', '.') }}</div>
      <div class="text-xs text-rose-600 mt-1">Yang ditolak rentang waktu ini</div>
    </div>
  </div>

  <!-- Charts Section -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    
    <!-- Bar Chart: Penyerapan Anggaran -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
      <h2 class="text-base font-bold text-slate-900 mb-1">Penyerapan Anggaran ({{ now()->year }})</h2>
      <p class="text-xs text-slate-500 mb-6">Perbandingan anggaran yang telah disetujui vs dana yang telah dicairkan.</p>
      <div class="relative h-64 w-full">
        <canvas id="barChart"></canvas>
      </div>
    </div>

    <!-- Pie Chart: Alokasi per Bidang -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
      <h2 class="text-base font-bold text-slate-900 mb-1">Alokasi Anggaran per Bidang</h2>
      <p class="text-xs text-slate-500 mb-6">Distribusi dana berdasarkan bidang sekolah (RAB disetujui).</p>
      <div class="relative h-64 flex items-center justify-center">
        @if(array_sum($dataDivisi) > 0)
          <canvas id="pieChart"></canvas>
        @else
          <div class="text-slate-400 text-xs italic">Belum ada data pencairan untuk ditampilkan.</div>
        @endif
      </div>
    </div>
  </div>

  <!-- Pengajuan Terbaru (Disetujui) -->
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-10">
    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
      <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Pengajuan Baru Selesai (30 Hari Terakhir)</h2>
      <a href="{{ route('pimpinan.riwayat') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Lihat Semua Riwayat &rarr;</a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs border-collapse">
        <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
          <tr>
            <th class="px-5 py-3 w-10 text-center">#</th>
            <th class="px-5 py-3">No. RAB &amp; Judul</th>
            <th class="px-5 py-3">Bidang</th>
            <th class="px-5 py-3 text-right">Nominal Cair</th>
            <th class="px-5 py-3 text-center">Tanggal Cair</th>
            <th class="px-5 py-3 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($riwayatPencairan ?? [] as $item)
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-3.5 font-mono font-bold text-indigo-700">{{ $item->no_rab }}</td>
              <td class="px-5 py-3.5 text-slate-600">{{ $item->divisi->nama_divisi ?? '-' }}</td>
              <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900">Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}</td>
              <td class="px-5 py-3.5 text-center text-slate-500 font-mono">
                {{ $item->updated_at ? $item->updated_at->format('d/m/Y') : '-' }}
              </td>
              <td class="px-5 py-3.5 text-center">
                @if($item->bukti_pencairan)
                  @php
                    $buktiUrl = Storage::url($item->bukti_pencairan);
                    $ext = strtolower(pathinfo($item->bukti_pencairan, PATHINFO_EXTENSION));
                    $isPdf = $ext === 'pdf';
                  @endphp
                  <button type="button" 
                          onclick="openBuktiModal('{{ $buktiUrl }}', '{{ $isPdf ? 'pdf' : 'image' }}')"
                          class="px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg text-xs font-semibold inline-flex items-center gap-1.5 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Lihat Bukti
                  </button>
                @else
                  <span class="text-slate-400 italic text-[10px]">Belum ada bukti</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-5 py-8 text-center text-slate-400">
                Tidak ada riwayat pencairan dana.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Bukti Pencairan -->
  <div id="buktiModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl mx-4 overflow-hidden transform scale-95 transition-transform duration-300" id="buktiModalContent">
      <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-slate-900">Bukti Pencairan Dana</h3>
        <button type="button" onclick="closeBuktiModal()" class="text-slate-400 hover:text-slate-700 p-1">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>
      <div class="p-6 bg-slate-50 min-h-[300px] flex items-center justify-center" id="buktiModalBody">
        <!-- Konten Bukti akan dimuat dengan JS -->
      </div>
      <div class="px-6 py-4 border-t border-slate-100 bg-white flex justify-end">
        <button type="button" onclick="closeBuktiModal()" class="px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-sm font-semibold transition-colors">
          Tutup
        </button>
      </div>
    </div>
  </div>

  <!-- Load Chart.js from CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    function openBuktiModal(url, type) {
      const modal = document.getElementById('buktiModal');
      const content = document.getElementById('buktiModalContent');
      const body = document.getElementById('buktiModalBody');
      
      if(type === 'image') {
        body.innerHTML = `<img src="${url}" alt="Bukti Pencairan" class="max-w-full max-h-[60vh] object-contain rounded-lg border border-slate-200 shadow-sm" />`;
      } else {
        body.innerHTML = `<iframe src="${url}" class="w-full h-[60vh] rounded-lg border border-slate-200 shadow-sm" title="Bukti Pencairan"></iframe>`;
      }
      
      modal.classList.remove('hidden');
      // trigger reflow
      void modal.offsetWidth;
      modal.classList.remove('opacity-0');
      content.classList.remove('scale-95');
    }

    function closeBuktiModal() {
      const modal = document.getElementById('buktiModal');
      const content = document.getElementById('buktiModalContent');
      
      modal.classList.add('opacity-0');
      content.classList.add('scale-95');
      
      setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('buktiModalBody').innerHTML = ''; // bersihkan konten
      }, 300);
    }

    // Tutup modal jika klik di luar box
    document.getElementById('buktiModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeBuktiModal();
      }
    });

    document.addEventListener('DOMContentLoaded', function() {
      // Data Penyerapan per Bulan
      const barCtx = document.getElementById('barChart');
      if (barCtx) {
        new Chart(barCtx, {
          type: 'bar',
          data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [
              {
                label: 'Anggaran Yang Telah Disetujui',
                data: @json($dataPenyerapanDisetujui),
                backgroundColor: '#10b981', // Emerald-500
                borderRadius: 4,
              },
              {
                label: 'Dana Yang Telah Dicairkan',
                data: @json($dataPenyerapanDicairkan),
                backgroundColor: '#3b82f6', // Blue-500
                borderRadius: 4,
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  callback: function(value) {
                    if (value >= 1000000) {
                      return 'Rp ' + (value / 1000000) + ' Jt';
                    } else if (value >= 1000) {
                      return 'Rp ' + (value / 1000) + ' Rb';
                    }
                    return 'Rp ' + value;
                  }
                }
              }
            },
            plugins: {
              legend: {
                display: true,
                position: 'top',
                labels: {
                  usePointStyle: true,
                  boxWidth: 8
                }
              },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    let label = context.dataset.label || '';
                    if (label) {
                      label += ': ';
                    }
                    if (context.parsed.y !== null) {
                      label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                    }
                    return label;
                  }
                }
              }
            }
          }
        });
      }

      // Data Alokasi per Bidang
      const pieCtx = document.getElementById('pieChart');
      if (pieCtx) {
        new Chart(pieCtx, {
          type: 'doughnut',
          data: {
            labels: @json($labelDivisi),
            datasets: [{
              data: @json($dataDivisi),
              backgroundColor: [
                '#4f46e5', // Indigo
                '#10b981', // Emerald
                '#f59e0b', // Amber
                '#3b82f6', // Blue
                '#f43f5e', // Rose
                '#8b5cf6', // Violet
                '#06b6d4', // Cyan
                '#f97316'  // Orange
              ],
              borderWidth: 1,
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: 'right',
                labels: {
                  boxWidth: 12,
                  font: {
                    size: 11
                  }
                }
              },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    let label = context.label || '';
                    if (label) {
                      label += ': ';
                    }
                    if (context.parsed !== null) {
                      label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed);
                    }
                    return label;
                  }
                }
              }
            }
          }
        });
      }
    });
  </script>
@endsection
