<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rak extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'rak';

    // Kolom yang boleh diisi
    protected $fillable = [
        'kode_rak',
        'lokasi',
    ];

    /**
     * Mendefinisikan relasi one-to-many ke model ItemBuku.
     * Satu rak bisa memiliki banyak item buku.
     */
    public function items()
    {
        return $this->hasMany(ItemBuku::class);
    }
}
