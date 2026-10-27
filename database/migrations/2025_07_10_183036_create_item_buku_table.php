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
        Schema::create('item_buku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade');
            $table->string('kode_barcode', 50)->unique();
            $table->enum('status', ['Tersedia', 'Dipinjam', 'Hilang', 'Perbaikan'])->default('Tersedia');
            $table->date('tanggal_masuk');
            $table->foreignId('rak_id')->constrained('rak')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_buku');
    }
};
