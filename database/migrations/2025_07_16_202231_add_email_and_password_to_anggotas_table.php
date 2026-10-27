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
        Schema::table('anggotas', function (Blueprint $table) {
            // Tambahkan kolom email setelah nama_anggota, harus unik
            $table->string('email')->unique()->nullable()->after('nama_anggota');
            // Pastikan kolom password sudah ada dan bisa diisi (sepertinya sudah ada di DB-mu)
            // $table->string('password')->nullable()->after('barcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            //
        });
    }
};
