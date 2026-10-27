<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('item_buku', function (Blueprint $table) {
            $table->string('nomor_eksemplar')->nullable()->after('kode_barcode');
        });
    }

    public function down()
    {
        Schema::table('item_buku', function (Blueprint $table) {
            $table->dropColumn('nomor_eksemplar');
        });
    }
};
