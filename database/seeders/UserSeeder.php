<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        User::truncate();

        $user = new \App\Models\User();
        $user->name = ucwords('admin');
        $user->email = 'admin@admin.com';
        $user->password = \Illuminate\Support\Facades\Hash::make('admin');
        $user->role_id = 1;
        $user->save();

        $user = new \App\Models\User();
        $user->name = ucwords('non admin');
        $user->email = 'nonadmin@admin.com';
        $user->password = \Illuminate\Support\Facades\Hash::make('nonadmin');
        $user->role_id = 2;
        $user->save();

        $user = new \App\Models\User();
        $user->name = ucwords('example user');
        $user->email = 'example@admin.com';
        $user->password = \Illuminate\Support\Facades\Hash::make('adminadmin');
        $user->role_id = 2;
        $user->save();

        Schema::enableForeignKeyConstraints();
    }
}
