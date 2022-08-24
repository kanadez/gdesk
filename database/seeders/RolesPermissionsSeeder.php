<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'admin',
            'developer',
            'user'
        ];
        $permissions = [
            ''
        ];

        foreach ($roles as $role) {
            $role = Role::create(['name' => $role]);
        }
        //$permission = Permission::create(['name' => 'edit articles']);

        $admins_emails = [
            'cyberzillahq@gmail.com',
            'admin@cyberzilla.io',
        ];
        $devs_emails = [
            'ins@allnight.ru',
            'kanadezzz@yandex.ru',
        ];

        $admins = User::select()->whereIn('email', $admins_emails)->get();
        $devs = User::select()->whereIn('email', $devs_emails)->get();
        $not_admins = User::select()->whereNotIn('email', $admins_emails)->get();

        foreach ($admins as $admin) {
            $admin->assignRole('admin');
        }

        foreach ($devs as $dev) {
            $dev->assignRole('developer');
        }

        foreach ($not_admins as $user) {
            $user->assignRole('user');
        }
    }
}
