<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new \App\Models\UserRole();
        $user->role_name = 'admin';
        $user->save();

        $user = new \App\Models\UserRole();
        $user->role_name = 'non-admin';
        $user->save();
    }
}
