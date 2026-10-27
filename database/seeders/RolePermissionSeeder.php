<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create Roles
        $admin = Role::create(['name' => 'admin']);
        $petugas = Role::create(['name' => 'pustakawan']);
        $siswa = Role::create(['name' => 'siswa']);

        // Create Users
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => bcrypt('password'),
        ]);
        $adminUser->assignRole($admin);

        $petugasUser = User::create([
            'name' => 'Petugas',
            'email' => 'petugas@mail.com',
            'password' => bcrypt('password'),
        ]);
        $petugasUser->assignRole($petugas);

        $siswaUser = User::create([
            'name' => 'Siswa',
            'email' => 'siswa@mail.com',
            'password' => bcrypt('password'),
        ]);
        $siswaUser->assignRole($siswa);
    }
}
