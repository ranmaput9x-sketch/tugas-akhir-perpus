<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Kategori;

class KategoriPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'guru']);
    }

    public function view(User $user, Kategori $kategori): bool
    {
        return $user->hasAnyRole(['admin', 'guru']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Kategori $kategori): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Kategori $kategori): bool
    {
        return $user->hasRole('admin');
    }

    public function restore(User $user, Kategori $kategori): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, Kategori $kategori): bool
    {
        return $user->hasRole('admin');
    }
}
