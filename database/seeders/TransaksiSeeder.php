<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaksi;
use App\Models\Buku;
use App\Models\Siswa;
use Carbon\Carbon;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $bukus = Buku::all();
        $siswas = Siswa::all();

        for ($i = 1; $i <= 20; $i++) {
            $tanggalPinjam = Carbon::now()->subDays(rand(1, 10));
            $tanggalKembali = (clone $tanggalPinjam)->addDays(7);
            $tanggalDikembalikan = rand(0, 1) ? (clone $tanggalKembali)->addDays(rand(0, 5)) : null;

            Transaksi::create([
                'buku_id'              => $bukus->random()->id,
                'siswa_id'             => $siswas->random()->id,
                'tanggal_pinjam'       => $tanggalPinjam,
                'tanggal_kembali'      => $tanggalKembali,
                'tanggal_dikembalikan' => $tanggalDikembalikan,
                'status'               => $tanggalDikembalikan ? 'dikembalikan' : 'dipinjam',
                'denda'                => $tanggalDikembalikan && $tanggalDikembalikan > $tanggalKembali ? rand(1000, 5000) : 0,
            ]);
        }
    }
}
