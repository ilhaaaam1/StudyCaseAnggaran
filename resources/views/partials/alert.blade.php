<!-- Alert / Flash Messages Component -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
  @if(session('success'))
    <div class="mb-4 flex items-center justify-between gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-sm">
      <div class="flex items-center gap-2.5">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-xs sm:text-sm font-medium">{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-sm">✕</button>
    </div>
  @endif

  @if(session('error'))
    <div class="mb-4 flex items-center justify-between gap-3 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl shadow-sm">
      <div class="flex items-center gap-2.5">
        <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-xs sm:text-sm font-medium">{{ session('error') }}</span>
      </div>
      <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 text-sm">✕</button>
    </div>
  @endif

  @if($errors->any())
    <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl shadow-sm">
      <div class="font-semibold text-xs sm:text-sm mb-1 flex items-center gap-2">
        <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        Mohon periksa kembali input formulir Anda:
      </div>
      <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-700 pl-6">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
</div>
