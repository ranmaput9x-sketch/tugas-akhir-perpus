<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeItemBukuIdNullableInPeminjamanTable extends Migration
{
    public function up()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // kalau ada foreign key, drop dulu
            $table->dropForeign(['item_buku_id']);
            // ubah jadi nullable
            $table->unsignedBigInteger('item_buku_id')
                ->nullable()
                ->change();
            // kalau perlu, pasang lagi FK
            $table->foreign('item_buku_id')
                ->references('id')
                ->on('item_buku')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropForeign(['item_buku_id']);
            $table->unsignedBigInteger('item_buku_id')
                ->nullable(false)
                ->change();
            $table->foreign('item_buku_id')
                ->references('id')
                ->on('item_buku');
        });
    }
}
