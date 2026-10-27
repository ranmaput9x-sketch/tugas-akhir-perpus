<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use Illuminate\Support\Str;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            Siswa::create([
                'nama'           => fake()->name(),
                'nis'            => fake()->unique()->numerify('22####'),
                'kelas'          => fake()->randomElement(['7A', '8B', '9C']),
                'jenis_kelamin'  => fake()->randomElement(['L', 'P']),
                'alamat'         => fake()->address(),
                'barcode'        => strtoupper(Str::random(8)),
            ]);
        }
    }
}
