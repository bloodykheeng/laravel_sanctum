<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        $rolesWithPermissions = [
            'Admin' => [
                'view approvals',
                'add approvals',
                'edit approvals',
                'delete approvals',

                'view programs',
                'add programs',
                'edit programs',
                'delete programs',


                'view departments',
                'add departments',
                'edit departments',
                'delete departments',

                'view projects',
                'add projects',
                'edit projects',
                'delete projects',


                'view sub projects',
                'add sub projects',
                'edit sub projects',
                'delete sub projects',


                'view metrics',
                'add metrics',
                'edit metrics',
                'delete metrics',


                'view outcomes',
                'add outcomes',
                'edit outcomes',
                'delete outcomes',

                'view reports',
                'add reports',
                'edit reports',
                'delete reports',


                'view users',
                'add users',
                'edit users',
                'delete users',


                'view settings',
                'add settings',
                'edit settings',
                'delete settings',


                'view directorates',
                'add directorates',
                'edit directorates',
                'delete directorates',


                'view sublocations',
                'add sublocations',
                'edit sublocations',
                'delete sublocations',


                'view data migrations',
                'add data migrations',
                'edit data migrations',
                'delete data migrations',

                'view roles',
                'add permissions on roles',
                'delete permissions on roles',


                'view permissions'


            ],
            'Commissioner' => [
                'view approvals',
                'add approvals',
                'edit approvals',
                'delete approvals',

                'view programs',



                'view departments',



                'view projects',



                'view sub projects',



                'view metrics',
                'add metrics',
                'edit metrics',
                'delete metrics',


                'view outcomes',
                'add outcomes',
                'edit outcomes',
                'delete outcomes',


                'view reports',
                'add reports',
                'edit reports',



                'view users',



                'view settings',
                'add settings',
                'edit settings',
                'delete settings',


                'view directorates',


                'view sublocations',



                'view data migrations',



                'view roles',




                'view permissions'
            ],
            'Manager' => [
                'view approvals',
                'add approvals',
                'edit approvals',
                'delete approvals',

                'view programs',
                'view departments',
                'view projects',
                'view sub projects',
                'view metrics',
                'add metrics',
                'edit metrics',
                'delete metrics',


                'view outcomes',
                'add outcomes',
                'edit outcomes',
                'delete outcomes',


                'view reports',
                'add reports',
                'edit reports',
                'delete reports',

                'view users',
                'add users',
                'edit users',
                'delete users',

                'view settings',
                'add settings',
                'edit settings',
                'delete settings',


                'view directorates',



                'view sublocations',
                'add sublocations',
                'edit sublocations',
                'delete sublocations',


                'view data migrations',
                'add data migrations',
                'edit data migrations',
                'delete data migrations',

                'view roles',

            ],
            'Supervisor' => [
                'view approvals',
                'add approvals',
                'edit approvals',
                'delete approvals',

                'view programs',
                'view departments',
                'view projects',
                'view sub projects',
                'view metrics',
                'add metrics',
                'edit metrics',
                'delete metrics',

                'view outcomes',
                'add outcomes',
                'edit outcomes',
                'delete outcomes',


                'view reports',
                'add reports',
                'edit reports',
                'delete reports',

                'view users',
                'add users',
                'edit users',
                'delete users',

                'view settings',
                'add settings',
                'edit settings',
                'delete settings',
                'view directorates',
                'view sublocations',
                'add sublocations',
                'edit sublocations',
                'delete sublocations',
                'view data migrations',
                'add data migrations',
                'edit data migrations',
                'delete data migrations',
                'view roles',
            ],
            'Standard User' => [
                'view programs',
                'view departments',
                'view projects',
                'view sub projects',
                'view metrics',
                'view outcomes',
                'view reports',
                'add reports',
                'edit reports',
                'view settings',
                'edit settings',
                'view directorates',

            ],
        ];

        $this->createRolesAndPermissions($rolesWithPermissions);
    }

    private function createRolesAndPermissions(array $rolesWithPermissions)
    {
        foreach ($rolesWithPermissions as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);

            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate(['name' => $permissionName]);
                if (!$role->hasPermissionTo($permission)) {
                    $role->givePermissionTo($permission);
                }
            }
        }
    }
}
