<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Divisi;
use App\Models\KategoriAnggaran;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FinanceKategoriPaguTest extends TestCase
{
    use RefreshDatabase;

    protected Pengguna $finance;

    protected Pengguna $staff;

    protected function setUp(): void
    {
        parent::setUp();

        $divisi = Divisi::create([
            'nama_divisi' => 'Keuangan',
        ]);

        $this->finance = Pengguna::create([
            'id_divisi' => $divisi->id_divisi,
            'nama_lengkap' => 'Staff Finance',
            'jabatan' => 'Bendahara',
            'email' => 'finance@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'finance',
        ]);

        $this->staff = Pengguna::create([
            'id_divisi' => $divisi->id_divisi,
            'nama_lengkap' => 'Staff Biasa',
            'jabatan' => 'Guru',
            'email' => 'guru@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);
    }

    public function test_finance_can_view_kategori_pagu_index_with_existing_data(): void
    {
        $kategori = KategoriAnggaran::create([
            'nama_kategori' => 'Sarana & Prasarana',
            'deskripsi' => 'Pengadaan meja kursi',
            'pagu_anggaran' => 50000000,
        ]);

        $response = $this->actingAs($this->finance)->get(route('finance.kategori.index'));

        $response->assertOk();
        $response->assertSee('Sarana & Prasarana');
        $response->assertSee(route('finance.kategori.update', $kategori->id));
    }

    public function test_finance_can_store_new_kategori(): void
    {
        $payload = [
            'nama_kategori' => 'Alat Tulis Kantor',
            'deskripsi' => 'Kertas, pulpen, dan tinta',
            'pagu_anggaran' => 15000000,
        ];

        $response = $this->actingAs($this->finance)
            ->post(route('finance.kategori.store'), $payload);

        $response->assertRedirect(route('finance.kategori.index'));
        $this->assertDatabaseHas('kategori_anggarans', [
            'nama_kategori' => 'Alat Tulis Kantor',
        ]);
    }

    public function test_finance_can_update_kategori(): void
    {
        $kategori = KategoriAnggaran::create([
            'nama_kategori' => 'Sarana & Prasarana Lama',
            'deskripsi' => 'Deskripsi lama',
            'pagu_anggaran' => 20000000,
        ]);

        $updatePayload = [
            'nama_kategori' => 'Sarana & Prasarana Baru',
            'deskripsi' => 'Deskripsi baru yang diperbarui',
            'pagu_anggaran' => 35000000,
        ];

        $response = $this->actingAs($this->finance)
            ->put(route('finance.kategori.update', $kategori->id), $updatePayload);

        $response->assertRedirect(route('finance.kategori.index'));
        $this->assertDatabaseHas('kategori_anggarans', [
            'id' => $kategori->id,
            'nama_kategori' => 'Sarana & Prasarana Baru',
            'pagu_anggaran' => 35000000,
        ]);
    }

    public function test_finance_can_delete_kategori(): void
    {
        $kategori = KategoriAnggaran::create([
            'nama_kategori' => 'Kategori Dihapus',
            'deskripsi' => 'Akan dihapus',
            'pagu_anggaran' => 5000000,
        ]);

        $response = $this->actingAs($this->finance)
            ->delete(route('finance.kategori.destroy', $kategori->id));

        $response->assertRedirect(route('finance.kategori.index'));
        $this->assertDatabaseMissing('kategori_anggarans', [
            'id' => $kategori->id,
        ]);
    }

    public function test_non_finance_cannot_access_kategori_pagu(): void
    {
        $response = $this->actingAs($this->staff)->get(route('finance.kategori.index'));

        $response->assertForbidden();
    }
}
