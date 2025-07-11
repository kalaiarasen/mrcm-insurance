<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create Admin role with permissions (between Super Admin and Agent)
        $adminRole = Role::create(['name' => 'Admin']);
        
        // Admin permissions (more than Agent, less than Super Admin)
        $adminPermissions = [
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
            'wallet-management.view',
            'agent-management.view',
            'client-management.view',
        ];
        
        $adminRole->givePermissionTo($adminPermissions);
        
        // Assign Admin role to all users who don't have any role (except Super Admin)
        $usersWithoutRoles = User::whereDoesntHave('roles')->get();
        
        foreach ($usersWithoutRoles as $user) {
            // Skip Super Admin user (admin@example.com)
            if ($user->email !== 'admin@example.com') {
                $user->assignRole('Admin');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove Admin role from users
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            // Remove role from all users
            $adminRole->users()->detach();
            // Delete the role
            $adminRole->delete();
        }
    }
};
