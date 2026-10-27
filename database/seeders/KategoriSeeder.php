<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        DB::table('kategori_buku')->insert([
            ['nama_kategori' => 'Novel', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Fiksi', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Ensiklopedia', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
