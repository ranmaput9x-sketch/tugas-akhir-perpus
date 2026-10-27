<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Milon\Barcode\DNS1D;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;
use App\Models\Buku;
use App\Models\wishlist;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Anggota extends Authenticatable
{
    protected $primaryKey = 'id_anggota';
    public $incrementing = false;
    protected $keyType = 'string';

    use HasFactory;

    protected $fillable = [
        'id_anggota',
        'nama_anggota',
        'nomor_induk',
        'no_telepon',
        'email', // Tambahkan email
        'password', // Tambahkan password
        'tanggal_lahir',
        'tanggal_registrasi',
        'berlaku_hingga',
        'jenis_kelamin',
        'alamat',
        'foto',
        'barcode',
        'status',
        'tingkat'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function generateBarcodeImage()
    {
        $text = $this->id_anggota;

        if (!$text || trim($text) === '') return;

        $barcode = new DNS1D();
        $barcode->setStorPath(storage_path('app/public/barcode/'));

        $filename = $this->id_anggota . '.png';
        $path = 'barcode/' . $filename;

        if (!Storage::disk('public')->exists($path)) {
            $image = $barcode->getBarcodePNG($text, 'C128', 2, 60);
            Storage::disk('public')->put($path, base64_decode($image));
        }
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'anggota_id', 'id');
    }
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'anggota_id', 'id_anggota');
    }
    public function getRouteKeyName()
    {
        return 'id'; // ✅ harus pakai id untuk URL /anggota/83/edit
    }
    public function getStatusAttribute($value)
    {
        // Cek jika status aslinya 'aktif' dan tanggal berlaku sudah lewat
        if ($value === 'aktif' && $this->berlaku_hingga && Carbon::parse($this->berlaku_hingga)->isPast()) {
            return 'kadaluwarsa';
        }

        // Jika tidak, kembalikan status asli dari database
        return $value;
    }
    public function activeItems()
    {
        return ItemBuku::whereHas(
            'peminjamans',
            fn($q) =>
            $q->where('anggota_id', $this->id)->where('status', 'Dipinjam')
        )->get();
    }
    public function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(
            Buku::class,     // 1. Model tujuan
            'wishlists',     // 2. Nama tabel pivot
            'anggota_id',    // 3. Foreign key untuk Anggota di tabel pivot
            'buku_id',       // 4. Foreign key untuk Buku di tabel pivot
            'id',            // 5. INI KUNCINYA: Local key di tabel Anggota (pakai 'id' yg integer)
            'id'             // 6. Local key di tabel Buku (pakai 'id' yg integer)
        );
    }
    // Tambahkan di dalam class Anggota
    public function komentarBuku()
    {
        return $this->hasMany(\App\Models\KomentarBuku::class, 'anggota_id');
    }
}
