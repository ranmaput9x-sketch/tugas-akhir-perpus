<?php

// app/Http/Controllers/WishlistController.php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct()
    {
        // Pastikan hanya anggota yang sudah login yang bisa akses
        $this->middleware('auth:anggota');
    }

    // app/Http/Controllers/WishlistController.php

    public function toggleWishlist(Buku $buku)
    {
        /** @var \App\Models\Anggota|null $anggota */ // <-- TAMBAHKAN BARIS INI
        $anggota = Auth::guard('anggota')->user();

        // Pastikan anggota ada sebelum lanjut
        if (!$anggota) {
            return back()->with('error', 'Kamu harus login dulu!');
        }

        // Cek apakah buku sudah ada di wishlist anggota
        $isExist = $anggota->wishlist()->where('buku_id', $buku->id)->exists();

        if ($isExist) {
            // Kalau sudah ada, hapus (detach)
            $anggota->wishlist()->detach($buku->id);
            return back()->with('success', 'Buku berhasil dihapus dari wishlist!');
        } else {
            // Kalau belum ada, tambahkan (attach)
            $anggota->wishlist()->attach($buku->id);
            return back()->with('success', 'Buku berhasil ditambahkan ke wishlist!');
        }
    }
}
