<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleAndPermissionSeeder::class,
        ]);

        $users = User::factory(10)->create();
        foreach ($users as $index => $user) {
            $role = $index < 5 ? 'CUSTOMER' : 'DRIVER';
            $user->assignRole($role);
        }

        Task::factory(10)->create();
    }
}
