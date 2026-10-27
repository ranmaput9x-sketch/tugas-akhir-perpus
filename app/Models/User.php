<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
// 🟢 Spatie Role Permission


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // ✅ Trait HasRoles WAJIB di sini

    /**
     * Atribut yang boleh diisi (mass assignable).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Atribut yang disembunyikan saat model dikonversi ke array/JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atribut yang dikonversi ke tipe tertentu.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
