<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Divisi;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BarangUploadPertemuan13Test extends TestCase
{
    use RefreshDatabase;

    protected Divisi $divisi;

    protected Pengguna $user;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->divisi = Divisi::create([
            'nama_divisi' => 'Teknologi Informasi',
        ]);

        $this->user = Pengguna::create([
            'id_divisi' => $this->divisi->id_divisi,
            'nama_lengkap' => 'Ahmad Fauzi',
            'jabatan' => 'Staf Sarpras',
            'email' => 'sarpras@sirab.local',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);
    }

    /**
     * Test tamu (guest) tidak diizinkan mengakses CRUD barang dan diredirect ke login.
     */
    public function test_guest_is_redirected_to_login_when_accessing_crud_routes(): void
    {
        $this->get(route('barang.index'))->assertRedirect(route('login'));
        $this->get(route('barang.create'))->assertRedirect(route('login'));
        $this->post(route('barang.store'), [])->assertRedirect(route('login'));
        $this->get('/barang/1')->assertRedirect(route('login'));
        $this->get('/barang/1/edit')->assertRedirect(route('login'));
        $this->put('/barang/1', [])->assertRedirect(route('login'));
        $this->delete('/barang/1')->assertRedirect(route('login'));
    }

    /**
     * Test Landing Page menampilkan galeri publik read-only dan bebas dari tombol CRUD.
     */
    public function test_landing_page_renders_read_only_gallery_and_cleans_crud_buttons(): void
    {
        $fakeImage = UploadedFile::fake()->image('meja_sekolah.jpg');
        $filename = 'meja_sekolah.jpg';
        $fakeImage->storeAs('barang', $filename, 'public');

        Barang::create([
            'nama' => 'Meja Kursi Siswa Kayu',
            'kategori' => 'Sarana & Prasarana',
            'jumlah' => 40,
            'kondisi' => 'baik',
            'keterangan' => 'Inventaris Ruang Kelas 4 SDN Sidokare 3',
            'gambar' => 'storage/barang/'.$filename,
        ]);

        $response = $this->get(route('login'));

        $response->assertStatus(200);
        // Bebas dari tombol CRUD
        $response->assertDontSee('Modul Inventaris (P13)');
        $response->assertDontSee('Tambah Barang');
        // Hero Section menampilkan foto statis bawaan sekolah
        $response->assertSee(asset('images/FotoKegiatan1.jpeg'));
        $response->assertSee(asset('images/FotoKegiatan2.jpeg'));
        // Menampilkan Galeri Publik Read-Only
        $response->assertSee('Galeri Dokumentasi', false);
        $response->assertSee('Meja Kursi Siswa Kayu');
        $response->assertSee(asset('storage/barang/'.$filename));

        // Test route welcome juga menampilkan foto statis di hero dan galeri di bawah
        $welcomeResponse = $this->get(route('welcome'));
        $welcomeResponse->assertStatus(200);
        $welcomeResponse->assertSee(asset('images/FotoKegiatan1.jpeg'));
        $welcomeResponse->assertSee('Galeri Dokumentasi', false);
        $welcomeResponse->assertSee('Meja Kursi Siswa Kayu');
    }

    /**
     * Test pengguna terautentikasi dapat mengakses index barang di dashboard dengan header GAMBAR.
     */
    public function test_authenticated_user_can_access_index_with_clean_labels(): void
    {
        $fakeImage = UploadedFile::fake()->image('laptop.jpg');
        $filename = 'laptop_test.jpg';
        $fakeImage->storeAs('barang', $filename, 'public');

        $barang = Barang::create([
            'nama' => 'Laptop Lenovo ThinkPad',
            'kategori' => 'Alat Elektronik',
            'jumlah' => 5,
            'kondisi' => 'baik',
            'keterangan' => 'Inventaris Lab Komputer SDN Sidokare 3',
            'gambar' => 'storage/barang/'.$filename,
        ]);

        $response = $this->actingAs($this->user)->get(route('barang.index'));

        $response->assertStatus(200);
        $response->assertSee('Laptop Lenovo ThinkPad');
        $response->assertSee('GAMBAR');
        $response->assertDontSee('Foto (P13)');
        $response->assertSee('Inventaris & Dokumentasi');
        $response->assertSee(route('barang.create'));
        // Slide 9: Thumbnail 36px
        $response->assertSee('width: 36px; height: 36px;', false);
    }

    /**
     * Test form upload create dapat diakses oleh user terautentikasi dan memiliki form enctype serta accept image.
     */
    public function test_authenticated_user_can_access_create_form(): void
    {
        $response = $this->actingAs($this->user)->get(route('barang.create'));

        $response->assertStatus(200);
        $response->assertSee('enctype="multipart/form-data"', false);
        $response->assertSee('name="gambar"', false);
        $response->assertSee('accept="image/*"', false);
    }

    /**
     * Test validasi tahap 2: Gagal jika kolom wajib kosong atau file bukan gambar.
     */
    public function test_store_validation_fails_on_invalid_data(): void
    {
        $response = $this->actingAs($this->user)->post(route('barang.store'), [
            'nama' => '',
            'kategori' => '',
            'jumlah' => -1,
            'kondisi' => 'invalid_kondisi',
            'gambar' => UploadedFile::fake()->create('document.pdf', 100),
        ]);

        $response->assertSessionHasErrors(['nama', 'kategori', 'jumlah', 'kondisi', 'gambar']);
        $this->assertDatabaseCount('barangs', 0);
    }

    /**
     * Test tahap 2, 3, 4: Simpan berkas fisik ke disk public dan path ke database.
     */
    public function test_store_saves_file_to_public_disk_and_creates_database_record(): void
    {
        $fakeImage = UploadedFile::fake()->image('proyektor_lab.jpg', 800, 600);

        $response = $this->actingAs($this->user)->post(route('barang.store'), [
            'nama' => 'Proyektor Epson EB-X500',
            'kategori' => 'Sarana & Prasarana',
            'jumlah' => 2,
            'kondisi' => 'baik',
            'keterangan' => 'Pengadaan Dana BOS 2026',
            'gambar' => $fakeImage,
        ]);

        $response->assertRedirect(route('barang.index'));
        $response->assertSessionHas('success');

        $barang = Barang::first();
        $this->assertNotNull($barang);
        $this->assertSame('Proyektor Epson EB-X500', $barang->nama);
        $this->assertStringStartsWith('storage/barang/', (string) $barang->gambar);

        $filename = basename($barang->gambar);
        Storage::disk('public')->assertExists('barang/'.$filename);
    }

    /**
     * Test tahap 5: Halaman show menampilkan detail dan tautan berkas gambar.
     */
    public function test_show_displays_barang_details_and_image(): void
    {
        $fakeImage = UploadedFile::fake()->image('sound_system.png');
        $filename = 'sound_system_test.png';
        $fakeImage->storeAs('barang', $filename, 'public');

        $barang = Barang::create([
            'nama' => 'Sound System Portable',
            'kategori' => 'Alat Elektronik',
            'jumlah' => 1,
            'kondisi' => 'baik',
            'keterangan' => 'Untuk upacara bendera',
            'gambar' => 'storage/barang/'.$filename,
        ]);

        $response = $this->actingAs($this->user)->get(route('barang.show', $barang->id));

        $response->assertStatus(200);
        $response->assertSee('Sound System Portable');
        $response->assertSee(asset('storage/barang/'.$filename));
        // Slide 9: Gambar detail maks 300px
        $response->assertSee('max-width: 300px', false);
    }

    /**
     * Test tahap 5: Halaman edit menampilkan pratinjau gambar 80px dan input accept image.
     */
    public function test_edit_displays_80px_preview_and_accept_image_attribute(): void
    {
        $fakeImage = UploadedFile::fake()->image('komputer.jpg');
        $filename = 'komputer_preview.jpg';
        $fakeImage->storeAs('barang', $filename, 'public');

        $barang = Barang::create([
            'nama' => 'PC All in One',
            'kategori' => 'Alat Elektronik',
            'jumlah' => 1,
            'kondisi' => 'baik',
            'keterangan' => 'Laboratorium',
            'gambar' => 'storage/barang/'.$filename,
        ]);

        $response = $this->actingAs($this->user)->get(route('barang.edit', $barang->id));

        $response->assertStatus(200);
        $response->assertSee('enctype="multipart/form-data"', false);
        $response->assertSee('accept="image/*"', false);
        // Slide 9: Pratinjau edit 80px
        $response->assertSee('width: 80px; height: 80px;', false);
    }

    /**
     * Test update mengganti berkas gambar lama dengan berkas baru dan menghapus fisik file lama.
     */
    public function test_update_replaces_old_image_and_deletes_from_storage(): void
    {
        $oldFile = UploadedFile::fake()->image('old_photo.jpg');
        $oldFilename = '111_old_photo.jpg';
        $oldFile->storeAs('barang', $oldFilename, 'public');

        $barang = Barang::create([
            'nama' => 'Printer Canon Pixma',
            'kategori' => 'Alat Elektronik',
            'jumlah' => 1,
            'kondisi' => 'baik',
            'keterangan' => 'Di ruang guru',
            'gambar' => 'storage/barang/'.$oldFilename,
        ]);

        Storage::disk('public')->assertExists('barang/'.$oldFilename);

        $newFile = UploadedFile::fake()->image('new_photo.jpg');

        $response = $this->actingAs($this->user)->put(route('barang.update', $barang->id), [
            'nama' => 'Printer Canon Pixma Terkalibrasi',
            'kategori' => 'Alat Elektronik',
            'jumlah' => 1,
            'kondisi' => 'baik',
            'keterangan' => 'Di ruang guru - baru servis',
            'gambar' => $newFile,
        ]);

        $response->assertRedirect(route('barang.index'));

        $barang->refresh();
        $this->assertSame('Printer Canon Pixma Terkalibrasi', $barang->nama);
        Storage::disk('public')->assertMissing('barang/'.$oldFilename);

        $newFilename = basename((string) $barang->gambar);
        Storage::disk('public')->assertExists('barang/'.$newFilename);
    }

    /**
     * Test update dengan opsi hapus_gambar menghapus file dari storage dan set null di database.
     */
    public function test_update_can_delete_image_via_checkbox(): void
    {
        $oldFile = UploadedFile::fake()->image('photo_to_delete.jpg');
        $filename = '222_photo_to_delete.jpg';
        $oldFile->storeAs('barang', $filename, 'public');

        $barang = Barang::create([
            'nama' => 'Meja Guru Kayu Jati',
            'kategori' => 'Sarana & Prasarana',
            'jumlah' => 10,
            'kondisi' => 'baik',
            'keterangan' => 'Ruang Guru',
            'gambar' => 'storage/barang/'.$filename,
        ]);

        Storage::disk('public')->assertExists('barang/'.$filename);

        $response = $this->actingAs($this->user)->put(route('barang.update', $barang->id), [
            'nama' => 'Meja Guru Kayu Jati',
            'kategori' => 'Sarana & Prasarana',
            'jumlah' => 10,
            'kondisi' => 'baik',
            'keterangan' => 'Ruang Guru',
            'hapus_gambar' => '1',
        ]);

        $response->assertRedirect(route('barang.index'));

        $barang->refresh();
        $this->assertNull($barang->gambar);
        Storage::disk('public')->assertMissing('barang/'.$filename);
    }

    /**
     * Test destroy menghapus file fisik di storage dan record di database.
     */
    public function test_destroy_deletes_physical_file_and_database_record(): void
    {
        $file = UploadedFile::fake()->image('item_to_destroy.jpg');
        $filename = '333_item_to_destroy.jpg';
        $file->storeAs('barang', $filename, 'public');

        $barang = Barang::create([
            'nama' => 'Papan Tulis Whiteboard',
            'kategori' => 'Sarana & Prasarana',
            'jumlah' => 1,
            'kondisi' => 'rusak',
            'keterangan' => 'Rusak berat / afkir',
            'gambar' => 'storage/barang/'.$filename,
        ]);

        Storage::disk('public')->assertExists('barang/'.$filename);

        $response = $this->actingAs($this->user)->delete(route('barang.destroy', $barang->id));

        $response->assertRedirect(route('barang.index'));
        $this->assertDatabaseMissing('barangs', ['id' => $barang->id]);
        Storage::disk('public')->assertMissing('barang/'.$filename);
    }
}
