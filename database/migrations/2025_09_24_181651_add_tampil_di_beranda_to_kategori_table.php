<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kategori', function (Blueprint $table) {
            // Tambahin kolom ini setelah 'kode_klasifikasi'
            $table->boolean('tampil_di_beranda')->default(false)->after('kode_klasifikasi');
        });
    }

    public function down(): void
    {
        Schema::table('kategori', function (Blueprint $table) {
            // Buat kalo-kalo mau di-rollback
            $table->dropColumn('tampil_di_beranda');
        });
    }
};
