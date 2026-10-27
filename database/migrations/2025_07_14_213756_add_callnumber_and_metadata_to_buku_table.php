<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->string('tempat_terbit')->nullable()->after('penerbit_id');
            $table->string('edisi')->nullable()->after('tempat_terbit');
            $table->string('nomor_panggil')->nullable()->after('edisi');
        });
    }

    public function down()
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->dropColumn(['tempat_terbit', 'edisi', 'nomor_panggil']);
        });
    }
};
