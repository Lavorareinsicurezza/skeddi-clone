<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // CRUD based modules (Route::resource)
        $resourceModules = [
            'companies',
            'users',
            'course-types',
            'document-types',
            'visit-types',
            'roles',
            'permissions',
            'company-documents',
            'company-workers',
            'company-course-types',
            'company-visit-types',
        ];

        // Page based modules (no CRUD)
        $pageModules = [
            'dashboard',
            'deadlines',
            'settings',
            'selected-company',
            'training-plan',
            'company-renewals',
            'chart',
        ];

        $crudActions = ['view', 'create', 'edit', 'update', 'delete'];

        // Create CRUD permissions
        foreach ($resourceModules as $module) {
            foreach ($crudActions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action} {$module}"
                ]);
            }
        }

        // Create view-only permissions
        foreach ($pageModules as $module) {
            Permission::firstOrCreate([
                'name' => "view {$module}"
            ]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        $allPermissions = Permission::pluck('name')->toArray();

        $admin->syncPermissions($allPermissions);
        $userPermissions = collect($allPermissions)->filter(function ($perm) {
            return str_starts_with($perm, 'view ');
        })->toArray();
        $userRole->syncPermissions($userPermissions);

        User::where('role', 'admin')->get()->each(function ($user) {
            $user->assignRole('admin');
        });
        User::where('role', 'user')->get()->each(function ($user) {
            $user->assignRole('user');
        });
    }
}
