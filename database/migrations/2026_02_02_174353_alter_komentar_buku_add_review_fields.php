<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('komentar_buku', function (Blueprint $table) {
            // "review" (ulasan) atau "komentar" (diskusi bebas)
            $table->string('tipe', 20)->default('review')->after('anggota_id');

            // recommended / neutral / not_recommended
            $table->string('rekomendasi', 32)->nullable()->after('tipe');

            // misal: Pemula / Menengah / Lanjutan atau Kelas 7/8/9
            $table->string('target_pembaca', 50)->nullable()->after('rekomendasi');

            // pending / approved / rejected
            $table->string('status', 20)->default('pending')->after('target_pembaca');

            // voting & report (tanpa tabel baru)
            $table->unsignedInteger('helpful_count')->default(0)->after('status');
            $table->unsignedInteger('reported_count')->default(0)->after('helpful_count');

            // simpan list anggota_id yg pernah vote/report biar 1 user 1 kali (tanpa tabel baru)
            $table->json('helpful_voters')->nullable()->after('reported_count');
            $table->json('report_voters')->nullable()->after('helpful_voters');

            $table->index(['buku_id', 'tipe', 'status']);
            $table->index(['anggota_id', 'tipe']);
        });
    }

    public function down(): void
    {
        Schema::table('komentar_buku', function (Blueprint $table) {

            // HAPUS index yang kita buat khusus untuk query anggota+tipe (ini aman)
            try {
                $table->dropIndex(['anggota_id', 'tipe']);
            } catch (\Throwable $e) {
                // kalau index-nya udah gak ada, skip
            }

            // JANGAN drop index ['buku_id','tipe','status'] karena MySQL bisa butuh index itu untuk FK.
            // Biarkan saja.

            // Drop kolom-kolom tambahan yang kita buat
            $table->dropColumn([
                'tipe',
                'rekomendasi',
                'target_pembaca',
                'status',
                'helpful_count',
                'reported_count',
                'helpful_voters',
                'report_voters',
            ]);
        });
    }
};
