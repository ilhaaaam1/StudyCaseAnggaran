@extends('layouts.app')

@section('title', 'Edit Data Akun Staff - SIRAB Kelompok-3')

@section('content')
  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-800">Panel Administrator</a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}" class="hover:text-slate-800">Manajemen Staff</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Edit Staff: {{ $user->nama_lengkap }}</span>
  </div>

  <div class="max-w-3xl mx-auto">
    <!-- Header Card -->
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mb-6">
      <div>
        <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
          Formulir Pembaruan Akun
        </div>
        <h1 class="text-2xl font-bold text-slate-900">Edit Data Akun Staff</h1>
        <p class="text-sm text-slate-500 mt-1">
          Perbarui identitas pengguna, divisi penugasan, atau reset kata sandi akun <strong class="text-slate-700">{{ $user->nama_lengkap }}</strong>.
        </p>
      </div>
      <a href="{{ route('admin.users.index') }}" 
         class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors shadow-sm">
        &larr; Kembali ke Daftar Staff
      </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm"
         x-data="{
           showModal: false,
           isSubmitting: false,
           showNoChangesAlert: false,
           alertTimeout: null,
           submitSafetyTimer: null,
           allowDirectSubmit: false,
           initial: {
             nama_lengkap: @js((string) old('nama_lengkap', $user->nama_lengkap)),
             email: @js((string) old('email', $user->email)),
             jabatan: @js((string) old('jabatan', $user->jabatan ?? '')),
             id_divisi: @js((string) old('id_divisi', $user->id_divisi)),
             password: ''
           },
           getForm() {
             return this.$refs.editForm || document.getElementById('editUserForm') || document.querySelector('form[action*=\'users\']');
           },
           init() {
             this.$nextTick(() => {
               try {
                 const form = this.getForm();
                 if (form) {
                   this.initial = {
                     nama_lengkap: (form.elements['nama_lengkap']?.value ?? this.initial.nama_lengkap ?? '').trim(),
                     email: (form.elements['email']?.value ?? this.initial.email ?? '').trim(),
                     jabatan: (form.elements['jabatan']?.value ?? this.initial.jabatan ?? '').trim(),
                     id_divisi: String(form.elements['id_divisi']?.value ?? this.initial.id_divisi ?? ''),
                     password: ''
                   };
                 }
               } catch (e) {
                 console.warn('Init state error:', e);
               }
             });
           },
           isDirty() {
             try {
               const form = this.getForm();
               if (!form) return true;

               const currentNama = (form.elements['nama_lengkap']?.value ?? '').trim();
               const currentEmail = (form.elements['email']?.value ?? '').trim().toLowerCase();
               const currentJabatan = (form.elements['jabatan']?.value ?? '').trim();
               const currentDivisi = String(form.elements['id_divisi']?.value ?? '');
               const currentPassword = (form.elements['password']?.value ?? '').trim();

               const initialNama = (this.initial.nama_lengkap ?? '').trim();
               const initialEmail = (this.initial.email ?? '').trim().toLowerCase();
               const initialJabatan = (this.initial.jabatan ?? '').trim();
               const initialDivisi = String(this.initial.id_divisi ?? '');

               const passwordChanged = currentPassword.length > 0;

               return (
                 currentNama !== initialNama ||
                 currentEmail !== initialEmail ||
                 currentJabatan !== initialJabatan ||
                 currentDivisi !== initialDivisi ||
                 passwordChanged
               );
             } catch (e) {
               console.warn('isDirty check fallback:', e);
               return true;
             }
           },
           hasChanges() {
             return this.isDirty();
           },
           handleSave() {
             try {
               const form = this.getForm();

               // 1. Pengecekan Perubahan Data (Is Dirty Check)
               if (!this.hasChanges()) {
                 this.showNoChangesAlert = true;
                 if (this.alertTimeout) clearTimeout(this.alertTimeout);
                 this.alertTimeout = setTimeout(() => {
                   this.showNoChangesAlert = false;
                 }, 4000);
                 return;
               }

               this.showNoChangesAlert = false;

               // 2. Jalankan validasi HTML5 bawaan form sebelum membuka modal
               if (form && typeof form.checkValidity === 'function' && !form.checkValidity()) {
                 form.reportValidity();
                 return;
               }

               // 3. Tampilkan popup modal konfirmasi
               this.showModal = true;
             } catch (err) {
               console.error('Error in handleSave:', err);
               this.showModal = true;
             }
           },
           handleFormSubmit(e) {
             if (this.allowDirectSubmit) {
               return true;
             }
             if (e && typeof e.preventDefault === 'function') {
               e.preventDefault();
             }
             this.handleSave();
           },
           closeModal() {
             if (this.isSubmitting) return;
             this.showModal = false;
             this.isSubmitting = false;
             this.allowDirectSubmit = false;
             if (this.submitSafetyTimer) clearTimeout(this.submitSafetyTimer);
           },
           confirmSubmit() {
             if (this.isSubmitting) return;
             this.isSubmitting = true;

             try {
               const form = this.getForm();
               if (!form) {
                 console.error('Form edit pengguna tidak ditemukan');
                 this.isSubmitting = false;
                 alert('Form data pengguna tidak ditemukan. Silakan muat ulang halaman.');
                 return;
               }

               // Validasi HTML5
               if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
                 this.isSubmitting = false;
                 this.showModal = false;
                 form.reportValidity();
                 return;
               }

               // Izinkan submit langsung lolos
               this.allowDirectSubmit = true;

               // Safety timeout agar tombol kembali normal jika koneksi terputus/stuck
               if (this.submitSafetyTimer) clearTimeout(this.submitSafetyTimer);
               this.submitSafetyTimer = setTimeout(() => {
                 if (this.isSubmitting) {
                   this.isSubmitting = false;
                   this.allowDirectSubmit = false;
                 }
               }, 8000);

               // Eksekusi submit native form
               if (typeof HTMLFormElement.prototype.submit === 'function') {
                 HTMLFormElement.prototype.submit.call(form);
               } else if (typeof form.submit === 'function') {
                 form.submit();
               } else {
                 form.requestSubmit();
               }
             } catch (err) {
               console.error('Error saat submit:', err);
               this.isSubmitting = false;
               this.allowDirectSubmit = false;
               alert('Gagal mengirim pembaruan: ' + (err.message || 'Silakan coba lagi.'));
             }
           }
         }">
      <form x-ref="editForm" 
            id="editUserForm"
            data-no-submit-lock="true"
            @submit="handleFormSubmit($event)" 
            action="{{ route('admin.users.update', $user->id_pengguna) }}" 
            method="POST" 
            class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Nama Lengkap -->
        <div>
          <label for="nama_lengkap" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
            Nama Lengkap Staf <span class="text-rose-500">*</span>
          </label>
          <input type="text" 
                 name="nama_lengkap" 
                 id="nama_lengkap" 
                 value="{{ old('nama_lengkap', $user->nama_lengkap) }}" 
                 @input="showNoChangesAlert = false"
                 required
                 placeholder="Contoh: Muhammad Fikri, S.Kom."
                 class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('nama_lengkap') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }} text-sm text-slate-800 bg-white transition-colors">
          @error('nama_lengkap')
            <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
              <span>⚠</span> {{ $message }}
            </p>
          @enderror
        </div>

        <!-- Email & Jabatan Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <!-- Email -->
          <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
              Alamat Email (Login) <span class="text-rose-500">*</span>
            </label>
            <input type="email" 
                   name="email" 
                   id="email" 
                   value="{{ old('email', $user->email) }}" 
                   @input="showNoChangesAlert = false"
                   required
                   placeholder="staf@sirab.local"
                   class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }} text-sm text-slate-800 bg-white transition-colors font-mono">
            @error('email')
              <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                <span>⚠</span> {{ $message }}
              </p>
            @enderror
          </div>

          <!-- Jabatan -->
          <div>
            <label for="jabatan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
              Jabatan / Posisi Kerja
            </label>
            <input type="text" 
                   name="jabatan" 
                   id="jabatan" 
                   value="{{ old('jabatan', $user->jabatan) }}" 
                   @input="showNoChangesAlert = false"
                   placeholder="Contoh: Staf IT / Analis Anggaran"
                   class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('jabatan') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }} text-sm text-slate-800 bg-white transition-colors">
            <span class="text-[10px] text-slate-400 mt-1 block">Opsional. Jika kosong, akan otomatis diset sesuai divisi.</span>
            @error('jabatan')
              <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                <span>⚠</span> {{ $message }}
              </p>
            @enderror
          </div>
        </div>

        <!-- Divisi Dropdown & Password Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <!-- Divisi -->
          <div>
            <label for="id_divisi" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
              Unit Kerja / Divisi <span class="text-rose-500">*</span>
            </label>
            <select name="id_divisi" 
                    id="id_divisi" 
                    @change="showNoChangesAlert = false"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('id_divisi') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }} text-sm text-slate-800 bg-white cursor-pointer transition-colors">
              <option value="">-- Pilih Unit Kerja / Divisi --</option>
              @foreach($divisi as $d)
                <option value="{{ $d->id_divisi }}" {{ (string) old('id_divisi', $user->id_divisi) === (string) $d->id_divisi ? 'selected' : '' }}>
                  {{ $d->nama_divisi }}
                </option>
              @endforeach
            </select>
            @error('id_divisi')
              <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                <span>⚠</span> {{ $message }}
              </p>
            @enderror
          </div>

          <!-- Password (Opsional) -->
          <div>
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
              Kata Sandi Baru <span class="text-slate-400 font-normal normal-case">(Opsional)</span>
            </label>
            <input type="password" 
                   name="password" 
                   id="password" 
                   minlength="6"
                   @input="showNoChangesAlert = false"
                   placeholder="Kosongkan jika tidak ingin mengubah kata sandi"
                   class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }} text-sm text-slate-800 bg-white transition-colors">
            <span class="text-[10px] text-slate-400 mt-1 block">Kosongkan jika tidak ingin mengubah kata sandi (minimal 6 karakter bila diisi).</span>
            @error('password')
              <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                <span>⚠</span> {{ $message }}
              </p>
            @enderror
          </div>
        </div>

        <!-- Role Badge Info -->
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs">
          <div class="flex items-center gap-2">
            <span class="text-indigo-600 font-bold">🔒 Hak Akses Akun:</span>
            <span class="text-slate-600">Terdaftar sebagai</span>
            <span class="font-bold text-blue-700 bg-blue-100 px-2.5 py-0.5 rounded-full border border-blue-200">Staff / Pemohon RAB (user)</span>
          </div>
          <span class="text-[10px] text-slate-400 font-mono">ID Pengguna: #{{ $user->id_pengguna }}</span>
        </div>

        <!-- Notifikasi Ringan (Dirty Check: Tidak Ada Perubahan) -->
        <div x-show="showNoChangesAlert" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2 scale-98"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 -translate-y-2 scale-98"
             style="display: none;"
             class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-center justify-between gap-3 shadow-xs">
          <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
              </svg>
            </div>
            <div>
              <span class="font-bold text-amber-800">Tidak ada perubahan data:</span>
              <span class="text-amber-700 ml-1">Tidak ada perubahan data untuk disimpan.</span>
            </div>
          </div>
          <button type="button" 
                  @click="showNoChangesAlert = false" 
                  class="text-amber-600 hover:text-amber-800 p-1 rounded-lg hover:bg-amber-100/60 transition-colors cursor-pointer"
                  title="Tutup pesan">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
          <a href="{{ route('admin.users.index') }}" 
             class="px-5 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition-colors">
            Batal
          </a>
          <button type="button" 
                  @click="handleSave()"
                  class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-md shadow-indigo-100 transition-all cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Simpan Perubahan
          </button>
        </div>
      </form>

      <!-- Modal Konfirmasi Pembaruan Data Pengguna -->
      <div x-show="showModal" 
           style="display: none;" 
           class="fixed inset-0 z-50 overflow-y-auto" 
           aria-labelledby="modal-title" 
           role="dialog" 
           aria-modal="true"
           @keydown.escape.window="closeModal()">
        <!-- Background Overlay (Backdrop blur) -->
        <div x-show="showModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 backdrop-blur-none"
             x-transition:enter-end="opacity-100 backdrop-blur-sm"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 backdrop-blur-sm"
             x-transition:leave-end="opacity-0 backdrop-blur-none"
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" 
             @click="closeModal()"></div>

        <!-- Modal Card Container -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
          <div x-show="showModal" 
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
               x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
               x-transition:leave="transition ease-in duration-200"
               x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
               x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
               class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100">
            
            <!-- Modal Body -->
            <div class="bg-white px-5 pb-5 pt-6 sm:p-6 sm:pb-5">
              <div class="sm:flex sm:items-start">
                <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 border border-indigo-100 sm:mx-0 sm:h-11 sm:w-11">
                  <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                  </svg>
                </div>
                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                  <h3 class="text-base sm:text-lg font-bold leading-6 text-slate-900" id="modal-title">
                    Konfirmasi Pembaruan Data Pengguna
                  </h3>
                  <div class="mt-2">
                    <p class="text-sm text-slate-500 leading-relaxed">
                      Apakah Anda yakin data yang diubah sudah benar dan ingin menyimpan perubahan ini?
                    </p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Modal Footer (Actions) -->
            <div class="bg-slate-50/80 px-5 py-3.5 sm:flex sm:flex-row-reverse sm:px-6 gap-2 border-t border-slate-100">
              <button type="button" 
                      @click="confirmSubmit()" 
                      :disabled="isSubmitting"
                      class="inline-flex w-full justify-center items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed sm:w-auto transition-colors cursor-pointer">
                <svg x-show="isSubmitting" style="display: none;" class="animate-spin -ml-0.5 mr-1 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="isSubmitting ? 'Menyimpan...' : 'Ya, Simpan'">Ya, Simpan</span>
              </button>
              <button type="button" 
                      @click="closeModal()" 
                      :disabled="isSubmitting"
                      class="mt-2 inline-flex w-full justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-xs border border-slate-300 hover:bg-slate-100 hover:text-slate-900 disabled:opacity-60 disabled:cursor-not-allowed sm:mt-0 sm:w-auto transition-colors cursor-pointer">
                Batal
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
