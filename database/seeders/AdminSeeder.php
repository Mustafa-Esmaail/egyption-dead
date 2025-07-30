<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Create super admin role if it doesn't exist
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'admin']);

        // Create super admin user
        $admin = Admin::firstOrCreate(
            ['email' => 'admin@egyptiancoach.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make('password123'),
                'phone' => '01000000000',
                'is_active' => true,
            ]
        );

        // Assign super admin role to the admin user
        $admin->assignRole($superAdminRole);

        // Give all permissions to super admin role
        $superAdminRole->givePermissionTo(\Spatie\Permission\Models\Permission::all());
    }
}
