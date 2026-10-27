<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wishlists';

    /**
     * The attributes that are mass assignable.
     * * Ini seperti 'izin' untuk Laravel, kolom mana saja yang boleh
     * diisi secara otomatis saat membuat data baru.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'anggota_id',
        'buku_id',
    ];

    /**
     * Mendefinisikan relasi ke model Anggota.
     * Setiap entri wishlist PASTI dimiliki oleh satu anggota.
     */
    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    /**
     * Mendefinisikan relasi ke model Buku.
     * Setiap entri wishlist PASTI merujuk ke satu buku.
     */
    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}
