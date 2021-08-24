<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'editar usuarios']);
        Permission::create(['name' => 'borrar usuarios']);
        Permission::create(['name' => 'crear usuarios']);
        Permission::create(['name' => 'listar usuarios']);
        Permission::create(['name' => 'editar roles']);
        Permission::create(['name' => 'borrar roles']);
        Permission::create(['name' => 'crear roles']);
        Permission::create(['name' => 'listar roles']);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
