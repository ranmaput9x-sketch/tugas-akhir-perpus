<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Matiin foreign key check biar bisa truncate meski ada relasi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Urutan truncate: relasi dulu, baru induk
        \App\Models\Transaksi::truncate();
        \App\Models\Siswa::truncate();
        \App\Models\Buku::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Panggil semua seeder
        $this->call([
            \Database\Seeders\BukuSeeder::class,
            \Database\Seeders\SiswaSeeder::class,
            \Database\Seeders\TransaksiSeeder::class,
            KategoriSeeder::class,
        ]);
    }
}
