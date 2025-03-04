<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed the default permissions
        $permissions = Permission::defaultPermissions();

        $permissionsData = [];
        foreach ($permissions as $perms) {
            $permissionsData[] = ['name' => $perms, 'guard_name' => 'web', 'created_at' => now()];
        }
        Permission::insert($permissionsData);

        $this->command->info('Default Permissions added.');

        // Ask for roles from input
        $inputRoles = $this->command
            ->ask('Enter roles in comma separate format. Admin has all permissions.', 'ADMIN,CUSTOMER,DRIVER');

        // Explode roles
        $rolesArray = explode(',', $inputRoles);

        // add roles
        foreach ($rolesArray as $roleName) {
            $role = Role::create(['name' => Str::upper(trim($roleName))]);

            if ($role->name === 'ADMIN') {
                // assign all permissions
                $role->syncPermissions(Permission::all());
                $this->command->info('Admin role granted all the permissions');
            } else {
                // for others by default only read access
                $role->syncPermissions(Permission::where('name', 'LIKE', 'view_%')->get());

                if ($role->name === 'DRIVER') {
                    //  DRIVER specific permissions
                    $role->givePermissionTo(Permission::whereIn('name', ['request_tasks'])->get());
                } elseif ($role->name === 'CUSTOMER') {
                    //  CUSTOMER specific permissions
                    $role->givePermissionTo(Permission::whereIn('name', ['assign_tasks'])->get());
                }

                $this->command->info('User role granted view permissions');
            }

            // create one user for each role
            $this->createUser($role);
        }

        $this->command->info('Roles ' . $inputRoles . ' added successfully');
    }

    /**
     * Create a user with given role
     */
    private function createUser(Role $role): void
    {
        if ($role->name == 'ADMIN') {
            $user = User::factory()->createOne([
                'name' => 'Asim Iteni',
                'email' => 'iteniasim.hulkstar@gmail.com',
            ]);
            $user->assignRole($role->name);

            $this->command->info('Here is your admin details to login:');
            $this->command->warn($user->email);
            $this->command->warn('Password is "password"');
        } else {
            $user = User::factory()->create();
            $user->assignRole($role->name);
        }
    }
}
