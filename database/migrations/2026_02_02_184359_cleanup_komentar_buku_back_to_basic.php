<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('komentar_buku', function (Blueprint $table) {

            // Drop index tambahan kalau ada (gunakan nama index yang umum Laravel buat)
            // Kalau index-nya tidak ada, MySQL akan error => kita handle pakai try/catch di luar (lihat bawah).
        });

        // Gunakan statement terpisah dengan try/catch supaya tidak gagal total
        try {
            Schema::table('komentar_buku', function (Blueprint $table) {
                $table->dropIndex('komentar_buku_buku_id_tipe_status_index');
            });
        } catch (\Throwable $e) {
        }

        try {
            Schema::table('komentar_buku', function (Blueprint $table) {
                $table->dropIndex('komentar_buku_anggota_id_tipe_index');
            });
        } catch (\Throwable $e) {
        }

        // Drop kolom tambahan (kalau kolomnya ada)
        Schema::table('komentar_buku', function (Blueprint $table) {
            $cols = [
                'tipe',
                'rekomendasi',
                'target_pembaca',
                'status',
                'helpful_count',
                'reported_count',
                'helpful_voters',
                'report_voters',
            ];

            foreach ($cols as $c) {
                try {
                    $table->dropColumn($c);
                } catch (\Throwable $e) {
                }
            }
        });
    }

    public function down(): void
    {
        // Kalau kamu benar-benar mau bisa "balik lagi" ke versi review fields,
        // barulah isi down() untuk menambahkan kolom-kolom itu kembali.
        // Tapi kamu bilang tidak mau, jadi boleh dibiarkan kosong.
    }
};
