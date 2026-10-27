<?php

// File: routes/api.php

// routes/api.php

use App\Models\Anggota;
use App\Models\ItemBuku;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AnggotaApiController;
use App\Http\Controllers\Api\BukuApiController;
use App\Http\Controllers\Api\AuthController;



Route::get('/anggota/{kode}', function ($kode) {
    return \App\Models\Anggota::where('id_anggota', $kode)
        ->orWhere('barcode', $kode)
        ->first(['id', 'id_anggota', 'nama_anggota']); // ✅ ambil nama_anggota juga!
});


Route::get('/item/{kode}', function ($kode) {
    return ItemBuku::with('buku')
        ->where('kode_barcode', $kode)
        ->where('status', 'Tersedia')
        ->first();
});
