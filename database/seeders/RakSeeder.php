<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RakSeeder extends Seeder
{
    public function run()
    {
        $raks = [
            ['kode' => 'A1', 'deskripsi' => 'Rak bagian depan dekat pintu'],
            ['kode' => 'A2', 'deskripsi' => 'Rak novel remaja'],
            ['kode' => 'B1', 'deskripsi' => 'Rak buku pelajaran kelas 7'],
            ['kode' => 'B2', 'deskripsi' => 'Rak buku pelajaran kelas 8'],
            ['kode' => 'C1', 'deskripsi' => 'Rak referensi ensiklopedia'],
        ];

        foreach ($raks as $rak) {
            DB::table('raks')->insert([
                'kode' => $rak['kode'],
                'deskripsi' => $rak['deskripsi'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
