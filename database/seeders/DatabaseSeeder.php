<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
        ]);

        $users = User::factory(10)->withPersonalTeam()->create();
        foreach ($users as $user) {
            $user->assignRole('CUSTOMER');
        }
    }
}
