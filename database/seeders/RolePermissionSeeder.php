<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions based on current menu items
        $permissions = [
            'dashboard.view',
            'for-your-action.view',
            'policy-holders.view',
            'policy-holders.create',
            'policy-holders.edit',
            'policy-holders.delete',
            'claims.view',
            'claims.create',
            'claims.edit',
            'claims.delete',
            'announcements.view',
            'announcements.create',
            'announcements.edit',
            'announcements.delete',
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'wallet-management.view',
            'agent-management.view',
            'client-management.view',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $adminRole = Role::create(['name' => 'Admin']);
        $clientRole = Role::create(['name' => 'Client']);
        $agentRole = Role::create(['name' => 'Agent']);

        // Super Admin gets all permissions
        $superAdminRole->givePermissionTo(Permission::all());

        // Admin permissions (almost all permissions except user/role management)
        $adminRole->givePermissionTo([
            'dashboard.view',
            'for-your-action.view',
            'policy-holders.view',
            'policy-holders.create',
            'policy-holders.edit',
            'policy-holders.delete',
            'claims.view',
            'claims.create',
            'claims.edit',
            'claims.delete',
            'announcements.view',
            'announcements.create',
            'announcements.edit',
            'announcements.delete',
            'wallet-management.view',
            'agent-management.view',
            'client-management.view',
        ]);

        // Client permissions (limited access)
        // $clientRole->givePermissionTo([
        //     'dashboard.view',
        //     'policy-holders.view',
        //     'claims.view',
        //     'claims.create',
        //     'announcements.view',
        // ]);

        // Agent permissions (more access than client)
        $agentRole->givePermissionTo([
            'dashboard.view',
            'for-your-action.view',
            'policy-holders.view',
            'policy-holders.create',
            'policy-holders.edit',
            'claims.view',
            'claims.create',
            'claims.edit',
            'announcements.view',
            'client-management.view',
        ]);

        // Create Super Admin user if it doesn't exist
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password123'),
            ]
        );

        // Assign Super Admin role
        $superAdmin->assignRole('Super Admin');

        $this->command->info('Roles and permissions created successfully!');
        $this->command->info('Super Admin user created: admin@example.com / password123');
    }
}
