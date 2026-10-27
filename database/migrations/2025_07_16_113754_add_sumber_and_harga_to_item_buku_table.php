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
        Schema::table('item_buku', function (Blueprint $table) {
            // Tambahkan kolom baru setelah 'status'
            $table->string('sumber_perolehan')->nullable()->after('status');
            $table->date('tanggal_perolehan')->nullable()->after('sumber_perolehan');
            $table->decimal('harga', 15, 2)->nullable()->after('tanggal_perolehan');

            // Ubah kolom tanggal_masuk agar nullable, karena sudah diwakili tanggal_perolehan
            $table->date('tanggal_masuk')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_buku', function (Blueprint $table) {
            $table->dropColumn(['sumber_perolehan', 'tanggal_perolehan', 'harga']);
            $table->date('tanggal_masuk')->nullable(false)->change();
        });
    }
};
