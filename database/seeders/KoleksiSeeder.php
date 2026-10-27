<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KoleksiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('koleksis')->insert([
            ['nama_koleksi' => 'Sirkulasi'],
            ['nama_koleksi' => 'Referensi'],
            ['nama_koleksi' => 'Digital'],
            ['nama_koleksi' => 'Donasi'],
            ['nama_koleksi' => 'Koleksi Langka'],
            ['nama_koleksi' => 'Koleksi Guru'],
            ['nama_koleksi' => 'Koleksi Siswa'],
        ]);
    }
}
