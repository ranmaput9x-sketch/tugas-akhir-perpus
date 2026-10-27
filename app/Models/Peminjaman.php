<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'anggota_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_pengembalian_aktual',
        'status',
        'late_days',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id', 'id');
    }

    public function items()
    {
        return $this->belongsToMany(
            \App\Models\ItemBuku::class,
            'peminjaman_item_buku',
            'peminjaman_id',
            'item_buku_id'
        )->withPivot(['kondisi', 'pengganti_item_buku_id', 'returned_at'])
            ->withTimestamps();
    }
}
