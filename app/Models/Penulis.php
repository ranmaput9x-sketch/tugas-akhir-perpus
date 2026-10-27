<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penulis extends Model
{
    use HasFactory;

    protected $fillable = ['nama_penulis'];
    protected $table = 'penulis';


    public function buku()
    {
        return $this->belongsToMany(
            \App\Models\Buku::class,
            'buku_penulis',   // nama pivot table
            'penulis_id',     // FK untuk model ini
            'buku_id'         // FK untuk model Buku
        );
    }
}
