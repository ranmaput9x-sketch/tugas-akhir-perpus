<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropKodeKlasifikasiFromBukuTable extends Migration
{
    public function up()
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->dropColumn('kode_klasifikasi');
        });
    }

    public function down()
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->string('kode_klasifikasi', 20)->nullable()->after('sub_judul');
        });
    }
}
