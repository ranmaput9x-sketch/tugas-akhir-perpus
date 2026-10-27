<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PenulisController;
use App\Http\Controllers\PenerbitController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\RakController;
use App\Http\Controllers\ItemBukuController;
use App\Http\Controllers\KoleksiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BukuImportController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SirkulasiHilangController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\Auth\AnggotaLoginController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\KomentarBukuController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute Homepage (root url), langsung dialihkan ke katalog
Route::get('/', function () {
    return redirect()->route('katalog.index');
});

/*
|--------------------------------------------------------------------------
| Anggota Authentication & Profile Routes
|--------------------------------------------------------------------------
*/

// --- RUTE UNTUK TAMU (YANG BELUM LOGIN) ---
Route::get('/masuk-anggota', [AnggotaLoginController::class, 'showLoginForm'])->name('anggota.login.form');
Route::post('/proses-masuk', [AnggotaLoginController::class, 'login'])->name('anggota.login.process');


// --- RUTE UNTUK ANGGOTA YANG SUDAH LOGIN (DILINDUNGI) ---
Route::middleware('auth:anggota')->group(function () {
    Route::post('/anggota/logout', [AnggotaLoginController::class, 'logout'])->name('anggota.logout');
    // Gunakan KatalogController untuk menampilkan & mengedit profil
    Route::get('/profil-anggota', [KatalogController::class, 'showProfile'])->name('anggota.profil');
    Route::get('/profil/edit/{anggota}', [KatalogController::class, 'showEditForm'])->name('profil.edit');
    Route::patch('/profil/{anggota}', [KatalogController::class, 'updateProfile'])->name('profil.update');
    Route::post('/wishlist/{buku}/toggle', [WishlistController::class, 'toggleWishlist'])
        ->name('wishlist.toggle');
    Route::post('/katalog/buku/{buku}/ulasan', [KomentarBukuController::class, 'store'])
        ->name('ulasan.store')
        ->middleware(['auth:anggota', 'throttle:10,1']);
});


/*
|--------------------------------------------------------------------------
| Public & Admin Routes
|--------------------------------------------------------------------------
*/

// Rute Publik (Katalog, dll)
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
Route::get('/katalog/{kategori?}', [KatalogController::class, 'index'])->name('katalog.index');
Route::get('/daftar-buku', [KatalogController::class, 'daftarBuku'])->name('katalog.daftar-buku');
Route::get('/katalog/buku/{buku}', [KatalogController::class, 'show'])->name('katalog.buku.show');
Route::get('/tentang-kami', [KatalogController::class, 'tentang'])->name('katalog.tentang');


// Rute Admin (dikelola oleh Auth::routes() dan middleware 'auth' standar)
// ==> TAMBAHKAN KODE INI KEMBALI <==
// Rute ini dibutuhkan oleh tema AdminLTE untuk redirect jika belum login
Route::get('admin/login', function () {
    return view('adminlte::auth.login');
})->name('admin.login');
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Grup untuk semua rute yang membutuhkan login
Route::middleware('auth')->group(function () {
    // Rute untuk mencetak label punggung (nomor panggil)
    Route::get('/buku/{buku}/cetak-label', [BukuController::class, 'cetakLabel'])->name('buku.cetak-label');
    // Rute untuk mencetak barcode eksemplar
    Route::get('/buku/{buku}/cetak-barcode', [BukuController::class, 'cetakBarcode'])->name('buku.cetak-barcode');
    // Route untuk fitur impor buku
    Route::get('/buku/impor', [BukuImportController::class, 'showForm'])->name('buku.impor.form');
    Route::post('/buku/impor', [BukuImportController::class, 'processImport'])->name('buku.impor.process');

    Route::post('/buku/{buku}/items', [ItemBukuController::class, 'store'])->name('items.store');
    Route::delete('buku/bulk-destroy', [BukuController::class, 'bulkDestroy'])->name('buku.bulkDestroy');
    Route::post('/buku/impor/check-content', [BukuImportController::class, 'checkContentDuplicates'])->name('buku.impor.checkContent');


    // Route untuk semua proses CRUD Buku
    Route::resource('buku', BukuController::class);
    Route::resource('penulis',  PenulisController::class);
    Route::resource('penerbit', PenerbitController::class);
    Route::resource('kategori', KategoriController::class);
    Route::post('/kategori/{kategori}/toggle-beranda', [KategoriController::class, 'toggleBeranda'])->name('admin.kategori.toggleBeranda');
    Route::resource('rak',      RakController::class);

    // 2. Rute untuk MENGELOLA semua eksemplar (index, edit, update, hapus)
    //    URL-nya: /items, /items/{item}/edit, dll.
    Route::resource('items', ItemBukuController::class)->except(['create', 'show', 'store']);
    Route::resource('koleksi', KoleksiController::class);

    Route::prefix('anggota')->name('anggota.')->group(function () {
        // --- Rute Custom (ditaruh di atas resource) ---
        Route::get('/import', [App\Http\Controllers\AnggotaImportController::class, 'form'])->name('import.form');
        Route::post('/import/preview', [App\Http\Controllers\AnggotaImportController::class, 'preview'])->name('import.preview');
        Route::post('/import/submit', [App\Http\Controllers\AnggotaImportController::class, 'submit'])->name('import.submit');

        Route::get('/export', [App\Http\Controllers\AnggotaExportController::class, 'form'])->name('export.form');
        Route::get('/export/execute', [App\Http\Controllers\AnggotaExportController::class, 'exportFiltered'])->name('export.execute');
        Route::post('/bulk-action', [App\Http\Controllers\AnggotaController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/form-cetak-kta-massal', [AnggotaController::class, 'showFormCetakKtaMassal'])->name('form-cetak-kta-massal');
        Route::post('/cetak-kta-massal', [AnggotaController::class, 'cetakKtaMassal'])->name('cetak-kta-massal');

        Route::get('/cetak-kta/{anggota}', [AnggotaController::class, 'cetakKta'])->name('cetak-kta');

        Route::post('/cek-duplicate', [AnggotaController::class, 'cekDuplicate'])->name('cek-duplicate');
    });

    Route::resource('anggota', AnggotaController::class)->parameters(['anggota' => 'anggota']);
    Route::get('/sirkulasi/hasil', [PeminjamanController::class, 'hasilSirkulasi'])
        ->name('sirkulasi.hasil');
    Route::prefix('sirkulasi')->group(function () {
        // Tampilkan daftar eksemplar hilang
        Route::get('hilang', [SirkulasiHilangController::class, 'index'])
            ->name('sirkulasi.hilang.index');
        Route::post('hilang/buat-buku', [SirkulasiHilangController::class, 'createBuku'])
            ->name('sirkulasi.hilang.createBuku');
        // update pivot dengan pengganti
        Route::post('update-pivot', [SirkulasiHilangController::class, 'updatePivot'])
            ->name('sirkulasi.hilang.updatePivot');
        // Proses quick‑create pengganti & update pivot
        Route::post('hilang', [SirkulasiHilangController::class, 'store'])
            ->name('sirkulasi.hilang.store');
    });
    Route::get('/sirkulasi', [App\Http\Controllers\PeminjamanController::class, 'halamanSirkulasi'])->name('sirkulasi.index');
    Route::prefix('sirkulasi')->name('sirkulasi.')->group(function () {
        Route::post('/cari-anggota', [App\Http\Controllers\PeminjamanController::class, 'cariAnggota'])->name('cari-anggota');
        Route::post('/cari-buku', [App\Http\Controllers\PeminjamanController::class, 'cariBuku'])->name('cari-buku');
        Route::post('/proses-pinjam', [App\Http\Controllers\PeminjamanController::class, 'prosesPinjam'])->name('proses-pinjam');
        Route::post('/proses-kembali', [App\Http\Controllers\PeminjamanController::class, 'prosesKembali'])->name('proses-kembali');
        Route::post('/peminjaman/{id}/perpanjang', [App\Http\Controllers\PeminjamanController::class, 'perpanjang'])
            ->name('perpanjang');
    });
    Route::resource('peminjaman', PeminjamanController::class);
    // Jika ada rute lain yang butuh login, letakkan di sini.
    // // laporan peminjaman & pengembalian
    // Route::get('laporan/sirkulasi', [LaporanController::class, 'index'])
    //     ->name('laporan.sirkulasi.form');
    // Route::post('laporan/sirkulasi/export', [LaporanController::class, 'export'])
    //     ->name('laporan.sirkulasi.export');
    Route::get('/laporan/sirkulasi', [ReportController::class, 'sirkulasi'])
        ->name('laporan.sirkulasi.form');

    Route::post('/laporan/sirkulasi', [ReportController::class, 'generate'])
        ->name('laporan.sirkulasi.generate');

    Route::get('/laporan/buku', [ReportController::class, 'buku'])
        ->name('laporan.buku.form');

    Route::post('/laporan/buku', [ReportController::class, 'generateBuku'])
        ->name('laporan.buku.generate');

    Route::get('/laporan/anggota', [ReportController::class, 'anggota'])
        ->name('laporan.anggota.form');

    Route::post('/laporan/anggota', [ReportController::class, 'generateAnggota'])
        ->name('laporan.anggota.generate');
    Route::get('/admin/ulasan', [KomentarBukuController::class, 'index'])
        ->name('admin.ulasan.index');
    Route::post('/ulasan/{komentar}/moderate', [KomentarBukuController::class, 'moderate'])
        ->name('ulasan.moderate');
});
