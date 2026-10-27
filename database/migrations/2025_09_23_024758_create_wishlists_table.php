<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            // Kunci asing ke tabel 'anggotas'
            $table->foreignId('anggota_id')->constrained('anggotas')->onDelete('cascade');
            // Kunci asing ke tabel 'buku'
            $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade');
            $table->timestamps();

            // Biar satu user nggak bisa nambahin buku yang sama berkali-kali
            $table->unique(['anggota_id', 'buku_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
