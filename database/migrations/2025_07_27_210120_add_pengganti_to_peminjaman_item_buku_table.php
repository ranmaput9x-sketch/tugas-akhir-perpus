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
        Schema::table('peminjaman_item_buku', function (Blueprint $t) {
            $t->unsignedBigInteger('pengganti_item_buku_id')->nullable()->after('kondisi');
            $t->foreign('pengganti_item_buku_id')
                ->references('id')->on('item_buku')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_item_buku', function (Blueprint $table) {
            //
        });
    }
};
