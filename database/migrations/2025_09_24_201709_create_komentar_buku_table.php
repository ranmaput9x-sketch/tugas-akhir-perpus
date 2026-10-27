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
        Schema::create('komentar_buku', function (Blueprint $table) {
            $table->id(); // ID unik untuk setiap komentar
            $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade'); // Nunjuk ke buku yg dikomen
            $table->foreignId('anggota_id')->constrained('anggotas')->onDelete('cascade'); // Nunjuk ke anggota yg komen
            $table->text('isi_komentar'); // Teks komentarnya
            $table->timestamps(); // Auto bikin kolom created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komentar_buku');
    }
};
