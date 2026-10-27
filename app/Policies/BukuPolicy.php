<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Buku;

class BukuPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('lihat buku');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('tambah buku');
    }

    public function update(User $user, Buku $buku)
    {
        return $user->hasPermissionTo('edit buku');
    }

    public function delete(User $user, Buku $buku)
    {
        return $user->hasPermissionTo('hapus buku');
    }
}
