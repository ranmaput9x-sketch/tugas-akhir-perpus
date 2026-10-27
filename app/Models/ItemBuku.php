<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemBuku extends Model
{
    use HasFactory;

    protected $table = 'item_buku';

    protected $fillable = [
        'buku_id',
        'kode_barcode',
        'nomor_eksemplar',
        'status',
        'tanggal_masuk',
        'rak_id',
        'tanggal_masuk',
        'sumber_perolehan',
        'tanggal_perolehan',
        'harga',
    ];
    protected $casts = [
        'tanggal_perolehan' => 'date',    // sekarang jadi Carbon
        'tanggal_masuk'     => 'datetime',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    public function rak()
    {
        return $this->belongsTo(Rak::class);
    }

    public function peminjamans()
    {
        return $this->belongsToMany(
            \App\Models\Peminjaman::class,
            'peminjaman_item_buku',
            'item_buku_id',
            'peminjaman_id'
        )->withPivot(['kondisi', 'pengganti_item_buku_id', 'returned_at'])
            ->withTimestamps();
    }
    public function pivotPengganti()
    {
        return $this->belongsTo(ItemBuku::class, 'pengganti_item_buku_id');
    }
}
