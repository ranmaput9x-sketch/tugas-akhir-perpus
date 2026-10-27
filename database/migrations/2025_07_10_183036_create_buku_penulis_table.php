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
        Schema::create('buku_penulis', function (Blueprint $table) {
            $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade');
            $table->foreignId('penulis_id')->constrained('penulis')->onDelete('restrict');
            $table->primary(['buku_id', 'penulis_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_penulis');
    }
};
