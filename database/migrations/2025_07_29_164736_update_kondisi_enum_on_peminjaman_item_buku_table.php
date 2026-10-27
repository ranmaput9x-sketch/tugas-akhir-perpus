<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateKondisiEnumOnPeminjamanItemBukuTable extends Migration
{
    public function up()
    {
        Schema::table('peminjaman_item_buku', function (Blueprint $table) {
            // ubah enum ke: baik, rusak, hilang (default tetap 'baik')
            $table->enum('kondisi', ['baik', 'rusak', 'hilang'])
                ->default('baik')
                ->change();
        });
    }

    public function down()
    {
        Schema::table('peminjaman_item_buku', function (Blueprint $table) {
            // kembalikan ke enum semula
            $table->enum('kondisi', ['baik', 'rusak'])
                ->default('baik')
                ->change();
        });
    }
}
