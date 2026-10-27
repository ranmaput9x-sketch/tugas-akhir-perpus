<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BukuSeeder extends Seeder
{
    public function run()
    {
        $kategoriList = DB::table('kategori_buku')->pluck('nama_kategori')->toArray();
        $rakList = DB::table('raks')->pluck('kode')->toArray();

        for ($i = 1; $i <= 20; $i++) {
            $judul = 'Buku Ke-' . $i;
            $isbn = '978' . rand(1000000000, 9999999999);
            $barcode = Str::slug($judul) . '-' . $isbn;

            DB::table('buku')->insert([
                'judul_buku'   => $judul,
                'pengarang'    => 'Pengarang ' . $i,
                'kategori'     => $kategoriList[array_rand($kategoriList)],
                'rak'          => $rakList[array_rand($rakList)],
                'tahun_terbit' => rand(2000, 2024),
                'isbn'         => $isbn,
                'stok'         => rand(1, 15),
                'cover'        => 'cover-default.jpg', // bisa diganti saat upload
                'barcode'      => $barcode,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}
