<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Anggota;

class GenerateAllBarcodes extends Command
{
    protected $signature = 'anggota:generate-barcodes';
    protected $description = 'Generate barcode PNG untuk semua anggota';

    public function handle()
    {
        $bar = $this->output->createProgressBar(Anggota::count());
        $bar->start();

        foreach (Anggota::all() as $anggota) {
            $anggota->generateBarcodeImage(); // method harus ada di model Anggota
            $bar->advance();
        }

        $bar->finish();
        $this->info("\n✔ Semua barcode berhasil digenerate!");
    }
}
