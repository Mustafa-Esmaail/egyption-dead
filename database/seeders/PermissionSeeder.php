<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Admins
            'show admins',
            'add admins',
            'edit admins',
            'delete admins',

            // Users
            'show users',
            'add users',
            'edit users',
            'delete users',

            // Permissions
            'show permission',
            'add permission',
            'edit permission',
            'delete permission',

            // Roles
            'show roles',
            'add roles',
            'edit roles',
            'delete roles',

            // Languages
            'show languages',
            'add languages',
            'edit languages',
            'delete languages',

            // Centers
            'show centers',
            'add centers',
            'edit centers',
            'delete centers',

            // Countries
            'show countries',
            'add countries',
            'edit countries',
            'delete countries',

            // Cities
            'show cities',
            'add cities',
            'edit cities',
            'delete cities',

            // Settings
            'show settings',
            'add settings',
            'edit settings',
            'delete settings',


            // Predicts
            'show predicts',
            'add predicts',
            'edit predicts',
            'delete predicts',

            // Clubs
            'show clubs',
            'add clubs',
            'edit clubs',
            'delete clubs',

            // Players
            'show players',
            'add players',
            'edit players',
            'delete players',

            // Teams
            'show teams',
            'add teams',
            'edit teams',
            'delete teams',

            // Vote Choices
            'show vote-choice',
            'add vote-choice',
            'edit vote-choice',
            'delete vote-choice',

            // Team Groups
            'show team_groups',
            'add team_groups',
            'edit team_groups',
            'delete team_groups',

            // Translation
            'show translation',
            'add translation',
            'edit translation',
            'delete translation',

            // Award Redeem Requests
            'show award-redeem-requests',
            'add award-redeem-requests',
            'edit award-redeem-requests',
            'delete award-redeem-requests',

            // permission
            'show permission',
            'add permission',
            'edit permission',
            'delete permission',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']);
        }
    }
}
