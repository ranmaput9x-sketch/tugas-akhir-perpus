<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKodeKlasifikasiToKategoriTable extends Migration
{
    public function up()
    {
        Schema::table('kategori', function (Blueprint $table) {
            $table->string('kode_klasifikasi', 20)
                ->nullable()
                ->after('nama_kategori')
                ->comment('Kode klasifikasi (DDC atau custom)');
        });
    }

    public function down()
    {
        Schema::table('kategori', function (Blueprint $table) {
            $table->dropColumn('kode_klasifikasi');
        });
    }
}
