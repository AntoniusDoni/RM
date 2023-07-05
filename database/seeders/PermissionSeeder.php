<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['id' => Str::ulid(), 'label' => 'Lihat Dashboard', 'name' => 'view-dashboard'],

            ['id' => Str::ulid(), 'label' => 'Tambah User', 'name' => 'create-user'],
            ['id' => Str::ulid(), 'label' => 'Ubah User', 'name' => 'update-user'],
            ['id' => Str::ulid(), 'label' => 'Lihat User', 'name' => 'view-user'],
            ['id' => Str::ulid(), 'label' => 'Hapus User', 'name' => 'delete-user'],

            ['id' => Str::ulid(), 'label' => 'Tambah Role', 'name' => 'create-role'],
            ['id' => Str::ulid(), 'label' => 'Ubah Role', 'name' => 'update-role'],
            ['id' => Str::ulid(), 'label' => 'Lihat Role', 'name' => 'view-role'],
            ['id' => Str::ulid(), 'label' => 'Hapus Role', 'name' => 'delete-role'],

            ['id' => Str::ulid(), 'label' => 'Tambah Pasien', 'name' => 'create-pasien'],
            ['id' => Str::ulid(), 'label' => 'Ubah Pasien', 'name' => 'update-pasien'],
            ['id' => Str::ulid(), 'label' => 'Lihat Pasien', 'name' => 'view-pasien'],
            ['id' => Str::ulid(), 'label' => 'Hapus Pasien', 'name' => 'delete-pasien'],

            ['id' => Str::ulid(), 'label' => 'Tambah Petugas', 'name' => 'create-petugas'],
            ['id' => Str::ulid(), 'label' => 'Ubah Petugas', 'name' => 'update-petugas'],
            ['id' => Str::ulid(), 'label' => 'Lihat Petugas', 'name' => 'view-petugas'],
            ['id' => Str::ulid(), 'label' => 'Hapus Petugas', 'name' => 'delete-petugas'],

            ['id' => Str::ulid(), 'label' => 'Tambah Ruang', 'name' => 'create-ruang'],
            ['id' => Str::ulid(), 'label' => 'Ubah Ruang', 'name' => 'update-ruang'],
            ['id' => Str::ulid(), 'label' => 'Lihat Ruang', 'name' => 'view-ruang'],
            ['id' => Str::ulid(), 'label' => 'Hapus Ruang', 'name' => 'delete-ruang'],

            ['id' => Str::ulid(), 'label' => 'lihat Peminjaman', 'name' => 'view-peminjaman'],
            ['id' => Str::ulid(), 'label' => 'Tambah Peminjaman', 'name' => 'create-peminjaman'],
            ['id' => Str::ulid(), 'label' => 'Ubah Peminjaman', 'name' => 'update-peminjaman'],
            ['id' => Str::ulid(), 'label' => 'Hapus Peminjaman', 'name' => 'delete-peminjaman'],

            ['id' => Str::ulid(), 'label' => 'Lihat Pengembalian', 'name' => 'view-pengembalian'],
            ['id' => Str::ulid(), 'label' => 'Tambah Pengembalian', 'name' => 'create-pengembalian'],
            ['id' => Str::ulid(), 'label' => 'Ubah Pengembalian', 'name' => 'update-pengembalian'],
            ['id' => Str::ulid(), 'label' => 'Hapus Pengembalian', 'name' => 'delete-pengembalian'],

            ['id' => Str::ulid(), 'label' => 'Riwayat Berkas', 'name' => 'view-riwayat'],

            // ['id' => Str::ulid(), 'label' => 'View Setting', 'name' => 'view-setting'],
        ];

        foreach ($permissions as $permission) {
            Permission::insert($permission);
        }

        $role = Role::create(['name' => 'admin']);

        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            $role->rolePermissions()->create(['permission_id' => $permission->id]);
        }

        User::create([
            'name' => 'Super Administrator',
            'email' => 'root@admin.com',
            'password' => bcrypt('password'),
        ]);

        $admin = User::create([
            'name' => 'Administator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
        ]);

        $setting = [];

        Setting::insert($setting);
    }
}
