<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // Menu::factory(10)->create();

        $admin = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'fauzanguci2@gmail.com',
            'password' => 'password',
        ]);

        $manager =  User::factory()->create([
            'name' => 'Manager',
            'email' => 'manager@gmail.com',
            'password' => 'password',
        ]);

        $cashier =  User::factory()->create([
            'name' => 'Cashier',
            'email' => 'cashier@gmail.com',
            'password' => 'password',
        ]);

        Menu::factory()->create([
            'name' => 'Superstore',
            'slug' => 'superstore',
            'workbook' => 'Superstore',
            'view' => 'Overview',
            'group' => json_encode(['admin', 'manager', 'cashier'])
        ]);

        Menu::factory()->create([
            'name' => 'World Indicators',
            'slug' => 'world-indicator',
            'workbook' => 'WorldIndicators',
            'view' => 'Population',
            'group' => json_encode(['admin', 'manager', 'cashier'])
        ]);

        Permission::create(['name' => 'view superstore']);
        Permission::create(['name' => 'view world indicator']);
        Permission::create(['name' => 'view statistics']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'view menus']);
        Permission::create(['name' => 'update users']);
        Permission::create(['name' => 'delete users']);

        Role::create(['name' => 'admin'])->givePermissionTo(['view superstore', 'view world indicator', 'view statistics', 'view users', 'update users', 'delete users', 'view menus']);
        Role::create(['name' => 'manager'])->givePermissionTo(['view superstore', 'view statistics']);
        Role::create(['name' => 'cashier'])->givePermissionTo(['view superstore']);

        // $menu->assignRole('admin');
        $admin->assignRole('admin');
        $manager->assignRole('manager');
        $cashier->assignRole('cashier');
    }
}
