<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRusakToItemBukuStatusEnum extends Migration
{
    public function up()
    {
        // Perlu doctrine/dbal agar ->change() bisa mengubah enum
        Schema::table('item_buku', function (Blueprint $table) {
            $table->enum('status', ['Tersedia', 'Dipinjam', 'Rusak', 'Hilang'])
                ->default('Tersedia')
                ->change();
        });
    }

    public function down()
    {
        Schema::table('item_buku', function (Blueprint $table) {
            $table->enum('status', ['Tersedia', 'Dipinjam', 'Hilang'])
                ->default('Tersedia')
                ->change();
        });
    }
}
