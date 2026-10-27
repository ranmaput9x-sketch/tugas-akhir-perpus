<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeKodeBarcodeNullable extends Migration
{
    public function up()
    {
        Schema::table('item_buku', function (Blueprint $table) {
            $table->string('kode_barcode', 12)
                ->nullable()
                ->change();
        });
    }

    public function down()
    {
        Schema::table('item_buku', function (Blueprint $table) {
            $table->string('kode_barcode', 12)
                ->nullable(false)
                ->change();
        });
    }
}
