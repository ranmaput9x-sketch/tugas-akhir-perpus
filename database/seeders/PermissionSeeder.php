<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Permissions
        $permissions = [
            'lihat buku',
            'tambah buku',
            'edit buku',
            'hapus buku',
            'akses dashboard',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // Assign to user
        $user = User::where('email', 'admin@mail.com')->first();
        if ($user) $user->assignRole('admin');
    }
}
