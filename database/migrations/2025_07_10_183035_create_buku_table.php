<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('judul_buku', 255);
            $table->string('sub_judul', 255)->nullable();
            $table->string('isbn', 20)->nullable();
            $table->year('tahun_terbit');
            $table->text('deskripsi')->nullable();
            $table->integer('jumlah_halaman');
            $table->string('url_cover', 255)->nullable();
            $table->foreignId('penerbit_id')->constrained('penerbit')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
