<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Koleksi extends Model
{
    use HasFactory;

    protected $fillable = ['nama_koleksi'];

    public function buku()
    {
        return $this->hasMany(Buku::class);
    }
}
