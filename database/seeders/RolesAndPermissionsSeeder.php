<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Currently not needed; keeping it here for now, just in case...
     */
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage tasks']);
        Permission::create(['name' => 'delete']);

        // create roles and assign permissions
        Role::create(['name' => 'admin'])->givePermissionTo(['manage users', 'manage tasks', 'delete']);
        Role::create(['name' => 'user']);
    }
}
