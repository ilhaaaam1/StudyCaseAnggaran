<!DOCTYPE html>
    <html lang="id">
    <head>
        <title>Uji Coba Create Data</title>
        <style>
            body { font-family: sans-serif; padding: 40px; }
            .form-group { margin-bottom: 20px; }
            input { padding: 8px; width: 300px; }
            .error { color: red; font-size: 14px; margin-top: 5px; }
            .sukses { color: green; font-weight: bold; padding: 10px; background: #e6ffe6; }
        </style>
    </head>
    <body>
        <h2>Simulasi Form Tambah Divisi</h2>

        @if(session('sukses'))
            <div class="sukses">{{ session('sukses') }}</div>
        @endif

        <form action="{{ route('uji.divisi.store') }}" method="POST">
            @csrf <!-- Wajib -->
            
            <div class="form-group">
                <label>Nama Divisi Baru:</label><br>
                <input type="text" name="nama_divisi" value="{{ old('nama_divisi') }}">
                
                <!-- Pesan Error Validasi -->
                @error('nama_divisi')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit">Simpan Data</button>
        </form>
    </body>
    </html>