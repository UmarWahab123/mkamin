<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_language","view_any_language","create_language","update_language","restore_language","restore_any_language","replicate_language","reorder_language","delete_language","delete_any_language","force_delete_language","force_delete_any_language","view_point::of::sale","view_any_point::of::sale","create_point::of::sale","update_point::of::sale","restore_point::of::sale","restore_any_point::of::sale","replicate_point::of::sale","reorder_point::of::sale","delete_point::of::sale","delete_any_point::of::sale","force_delete_point::of::sale","force_delete_any_point::of::sale","view_product::and::service","view_any_product::and::service","create_product::and::service","update_product::and::service","restore_product::and::service","restore_any_product::and::service","replicate_product::and::service","reorder_product::and::service","delete_product::and::service","delete_any_product::and::service","force_delete_product::and::service","force_delete_any_product::and::service","view_reservation","view_any_reservation","create_reservation","update_reservation","restore_reservation","restore_any_reservation","replicate_reservation","reorder_reservation","delete_reservation","delete_any_reservation","force_delete_reservation","force_delete_any_reservation","view_reservation::setting","view_any_reservation::setting","create_reservation::setting","update_reservation::setting","restore_reservation::setting","restore_any_reservation::setting","replicate_reservation::setting","reorder_reservation::setting","delete_reservation::setting","delete_any_reservation::setting","force_delete_reservation::setting","force_delete_any_reservation::setting","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_service::category","view_any_service::category","create_service::category","update_service::category","restore_service::category","restore_any_service::category","replicate_service::category","reorder_service::category","delete_service::category","delete_any_service::category","force_delete_service::category","force_delete_any_service::category","view_setting","view_any_setting","create_setting","update_setting","restore_setting","restore_any_setting","replicate_setting","reorder_setting","delete_setting","delete_any_setting","force_delete_setting","force_delete_any_setting"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
