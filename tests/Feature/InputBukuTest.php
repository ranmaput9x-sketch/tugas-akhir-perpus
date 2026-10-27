<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Penulis;
use App\Models\Penerbit;
use App\Models\Koleksi;
use App\Models\User;
use Spatie\Permission\Models\Role;

class InputBukuTest extends TestCase
{
    use RefreshDatabase;
    protected $user;
    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        $this->user = User::factory()->create();
        $this->user->assignRole('admin');

        // Seeder atau factory dummy
        Kategori::factory()->create(['id' => 1, 'nama_kategori' => 'Fiksi']);
        Penulis::factory()->create(['id' => 1, 'nama_penulis' => 'Rangga']);
        Penerbit::factory()->create(['id' => 1, 'nama_penerbit' => 'Cihuy Books']);
        Koleksi::factory()->create(['id' => 1, 'nama_koleksi' => 'Digital']);
        Koleksi::factory()->create(['id' => 2, 'nama_koleksi' => 'Sirkulasi']);
    }

    public function test_input_buku_digital_tanpa_ebook_should_fail()
    {
        $this->actingAs($this->user); // <– PENTING!
        $response = $this->post('/buku', [
            'judul_buku' => 'Ebook Tanpa File',
            'sub_judul' => 'Step test',
            'kategori_id' => 1,
            'koleksi_id' => 1, // Digital
            'penulis_id' => 1,
            'penerbit_id' => 1,
            'tahun_terbit' => 2024,
            'jumlah_halaman' => 100,
        ]);

        $response->assertSessionHasErrors(['file_ebook']);
    }

    public function test_input_buku_digital_dengan_ebook_should_pass()
    {
        Storage::fake('public');
        $this->actingAs($this->user); // <– PENTING!
        $response = $this->post('/buku', [
            'judul_buku' => 'Ebook Valid',
            'sub_judul' => 'Step test',
            'kategori_id' => 1,
            'koleksi_id' => 1, // Digital
            'penulis_id' => 1,
            'penerbit_id' => 1,
            'tahun_terbit' => 2024,
            'jumlah_halaman' => 120,
            'file_ebook' => UploadedFile::fake()->create('contoh.pdf', 1000, 'application/pdf'),
        ]);

        $response->assertRedirect(); // Redirect to show or index
        $this->assertDatabaseHas('buku', ['judul_buku' => 'Ebook Valid']);
    }

    public function test_input_buku_fisik_tanpa_ebook_should_pass()
    {
        $this->actingAs($this->user); // <– PENTING!
        $response = $this->post('/buku', [
            'judul_buku' => 'Buku Fisik',
            'sub_judul' => 'Langsung ke rak',
            'kategori_id' => 1,
            'koleksi_id' => 2, // Sirkulasi (fisik)
            'penulis_id' => 1,
            'penerbit_id' => 1,
            'tahun_terbit' => 2023,
            'jumlah_halaman' => 200,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('buku', ['judul_buku' => 'Buku Fisik']);
    }
}
