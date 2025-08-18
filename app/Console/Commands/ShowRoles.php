<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class ShowRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:show-roles {--fix : Fix missing roles and permissions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show all roles and their permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('fix')) {
            $this->info('Fixing roles and permissions...');
            $this->fixRolesAndPermissions();
        }

        // Display results from direct DB query
        $this->info('Direct DB Query Results:');
        $rolesFromDB = DB::table('roles')->get();

        foreach ($rolesFromDB as $role) {
            $this->comment("Role: {$role->name} (ID: {$role->id})");

            // Get permissions for this role
            $permissionIds = DB::table('role_has_permissions')
                ->where('role_id', $role->id)
                ->pluck('permission_id');

            $permissions = DB::table('permissions')
                ->whereIn('id', $permissionIds)
                ->get();

            if ($permissions->count() === 0) {
                $this->warn('  No permissions assigned.');
            } else {
                $this->info('  Permissions:');
                foreach ($permissions as $permission) {
                    $this->line("  - {$permission->name}");
                }
            }

            $this->newLine();
        }

        // Also show through the model for comparison
        $this->info('Model Query Results:');
        $roles = Role::with('permissions')->get();

        foreach ($roles as $role) {
            $this->comment("Role: {$role->name}");
            $this->info('Permissions:');

            if ($role->permissions->count() === 0) {
                $this->warn('  No permissions assigned.');
            } else {
                foreach ($role->permissions as $permission) {
                    $this->line("  - {$permission->name}");
                }
            }

            $this->newLine();
        }

        return Command::SUCCESS;
    }

    private function fixRolesAndPermissions()
    {
        // Create super_admin role if it doesn't exist
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        // Create point_of_sale role if it doesn't exist
        $posRole = Role::firstOrCreate(['name' => 'point_of_sale', 'guard_name' => 'web']);

        // Make sure all permissions exist
        $permissions = [
            // Point of Sale permissions
            'view_point_of_sale',
            'view_any_point_of_sale',
            'create_point_of_sale',
            'update_point_of_sale',
            'delete_point_of_sale',
            'delete_any_point_of_sale',

            // Service permissions
            'view_service',
            'view_any_service',
            'create_service',
            'update_service',
            'delete_service',
            'delete_any_service',
        ];

        // Create permissions if they don't exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign all permissions to super_admin
        $superAdminRole->syncPermissions(Permission::all());

        // Assign limited permissions to point_of_sale
        $posRole->syncPermissions([
            'view_point_of_sale',
            'view_any_point_of_sale',
            'update_point_of_sale',
            'view_service',
            'view_any_service',
        ]);

        $this->info('Roles and permissions fixed successfully!');
    }
}
