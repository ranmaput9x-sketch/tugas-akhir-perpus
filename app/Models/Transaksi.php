<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = [
        'buku_id',
        'siswa_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_dikembalikan',
        'status',
        'denda',
    ];

    public function buku()
    {
        return $this->belongsTo(\App\Models\Buku::class, 'buku_id'); // ← pakai 'buku_id'
    }
}
