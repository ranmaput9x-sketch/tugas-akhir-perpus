<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KategoriSmpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('kategori')->insert([
            [
                'nama_kategori'   => 'Bahasa Indonesia',
                'kode_klasifikasi'  => '100',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nama_kategori'   => 'Bahasa Inggris',
                'kode_klasifikasi'  => '200',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nama_kategori'   => 'Matematika',
                'kode_klasifikasi'  => '300',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nama_kategori'   => 'Ilmu Pengetahuan Alam (IPA)',
                'kode_klasifikasi'  => '400',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nama_kategori'   => 'Ilmu Pengetahuan Sosial (IPS)',
                'kode_klasifikasi'  => '500',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nama_kategori'   => 'PPKn & Kewarganegaraan',
                'kode_klasifikasi'  => '600',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nama_kategori'   => 'Seni Budaya & Prakarya',
                'kode_klasifikasi'  => '700',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nama_kategori'   => 'PJOK',
                'kode_klasifikasi'  => '800',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nama_kategori'   => 'Agama & Budi Pekerti',
                'kode_klasifikasi'  => '900',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nama_kategori'   => 'Referensi (Kamus, Ensiklopedia, Atlas)',
                'kode_klasifikasi'  => '000',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nama_kategori'   => 'Fiksi Anak & Remaja',
                'kode_klasifikasi'  => '801',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ]);
    }
}
