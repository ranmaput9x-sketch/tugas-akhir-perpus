<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKondisiToPeminjamanItemBukuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peminjaman_item_buku', function (Blueprint $table) {
            $table->enum('kondisi', ['baik', 'rusak'])
                ->default('baik')
                ->after('item_buku_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peminjaman_item_buku', function (Blueprint $table) {
            $table->dropColumn('kondisi');
        });
    }
}
