<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Anggota;
use App\Models\ItemBuku;
use Illuminate\Support\Facades\DB;

class GenerateBarcode extends Command
{
    protected $signature = 'barcode:generate';
    protected $description = 'Generate barcode numeric untuk anggota dan item buku';

    public function handle()
    {
        DB::transaction(function () {
            // Anggota
            $this->info("⏳ Mengupdate barcode anggota...");
            Anggota::chunk(100, function ($rows) {
                foreach ($rows as $anggota) {
                    $barcode = '20' . str_pad($anggota->id, 6, '0', STR_PAD_LEFT);
                    $anggota->barcode = $barcode;
                    $anggota->save();
                }
            });

            // Item Buku
            $this->info("⏳ Mengupdate kode_barcode item buku...");
            ItemBuku::chunk(100, function ($rows) {
                foreach ($rows as $item) {
                    $barcode = '25' . date('Y') . str_pad($item->id, 4, '0', STR_PAD_LEFT);
                    $item->kode_barcode = $barcode;
                    $item->save();
                }
            });
        });

        $this->info("✅ Barcode anggota & item buku berhasil di-generate ulang!");
    }
}
