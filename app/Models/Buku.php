<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Anggota;
use App\Models\ItemBuku;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Buku extends Model
{
    use HasFactory;

    // Hapus baris $guarded
    // protected $guarded = ['id']; 

    protected $table = 'buku';

    // Cukup gunakan $fillable untuk keamanan mass assignment
    protected $fillable = [
        'judul_buku',
        'sub_judul',
        'isbn',
        'tahun_terbit',
        'deskripsi',
        'jumlah_halaman',
        'url_cover',
        'file_ebook',
        'format_ebook',
        'koleksi_id',
        'penerbit_id',
        'tempat_terbit',
        'edisi',
        'nomor_panggil',
        'kode_barcode'
    ];

    public function kategori()
    {
        return $this->belongsToMany(Kategori::class, 'buku_kategori', 'buku_id', 'kategori_id');
    }

    public function koleksi()
    {
        return $this->belongsTo(Koleksi::class);
    }

    public function penulis()
    {
        return $this->belongsToMany(Penulis::class, 'buku_penulis', 'buku_id', 'penulis_id');
    }

    public function penerbit()
    {
        return $this->belongsTo(Penerbit::class);
    }

    public function items()
    {
        return $this->hasMany(ItemBuku::class, 'buku_id');
    }

    public function wishlistedBy(): BelongsToMany
    {
        return $this->belongsToMany(
            Anggota::class,  // 1. Model tujuan
            'wishlists',     // 2. Nama tabel pivot
            'buku_id',       // 3. Foreign key untuk Buku di tabel pivot
            'anggota_id',    // 4. Foreign key untuk Anggota di tabel pivot
            'id',            // 5. Local key di tabel Buku (pakai 'id')
            'id'             // 6. INI KUNCINYA: Local key di tabel Anggota (pakai 'id')
        );
    }

    public function peminjamanItems()
    {
        return $this->hasManyThrough(
            Peminjaman::class, // Model tujuan akhir
            ItemBuku::class,           // Model perantara
            'buku_id',                             // Foreign key di tabel perantara (item_buku)
            'item_buku_id',                        // Foreign key di tabel tujuan (peminjaman_item_buku)
            'id',                                  // Local key di tabel awal (buku)
            'id'                                   // Local key di tabel perantara (item_buku)
        );
    }
    public function komentar()
    {
        return $this->hasMany(\App\Models\KomentarBuku::class, 'buku_id');
    }
}
