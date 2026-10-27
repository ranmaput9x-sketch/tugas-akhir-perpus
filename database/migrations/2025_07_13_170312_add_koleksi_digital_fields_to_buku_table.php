<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            // Tambahan kolom
            $table->string('file_ebook')->nullable()->after('url_cover');
            $table->string('format_ebook')->nullable()->after('file_ebook');
            $table->string('kode_klasifikasi')->nullable()->after('format_ebook');

            // Tambah FK koleksi_id jika belum
            if (!Schema::hasColumn('buku', 'koleksi_id')) {
                $table->unsignedBigInteger('koleksi_id')->nullable()->after('penerbit_id');
            }

            // Cek dulu apakah kolom sudah ada dan belum ada FK-nya
            $table->foreign('koleksi_id')->references('id')->on('koleksis')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->dropForeign(['koleksi_id']);
            $table->dropColumn(['file_ebook', 'format_ebook', 'kode_klasifikasi', 'koleksi_id']);
        });
    }
};
