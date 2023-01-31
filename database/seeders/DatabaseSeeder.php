<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\label_navigation;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // seed role
        \App\Models\user_role::create([
            'role' => 'Super Admin'
        ]);

        \App\Models\user_role::create([
            'role' => 'Admin'
        ]);

        // seed user admin
        \App\Models\User::create([
            'user_id' => Str::uuid(),
            'name' => 'Admin',
            'email' => 'itpublishing@gmail.com',
            'password' => Hash::make('itpublishing'),
            'is_active' => 1,
            'role_id' => 1
        ]);


        // label and navigation
        label_navigation::create([
            'label' => 'Home'
        ]);

        label_navigation::create([
            'label' => 'Config'
        ]);


        $dashboard = Str::uuid();
        \App\Models\navigation::create([
            'nav_id' => $dashboard,
            'id_label' => 1,
            'url' => '/administrator',
            'navigation' => "Dashboard",
            'icon' => 'dashboard',
            'is_active' => 1
        ]);

        \App\Models\user_access::create([
            'access_id' => Str::uuid(),
            'role_id' => 1,
            'nav_id' => $dashboard
        ]);

        $navigation = Str::uuid();
        \App\Models\navigation::create([
            'nav_id' => $navigation,
            'id_label' => 2,
            'url' => '/administrator/navigation',
            'navigation' => "Navigation",
            'icon' => 'navigation',
            'is_active' => 1
        ]);

        \App\Models\user_access::create([
            'access_id' => Str::uuid(),
            'role_id' => 1,
            'nav_id' => $navigation
        ]);


        $user_access = Str::uuid();
        \App\Models\navigation::create([
            'nav_id' => $user_access,
            'id_label' => 2,
            'url' => '/administrator/user_access',
            'navigation' => "User Access",
            'icon' => 'user access',
            'is_active' => 1
        ]);

        \App\Models\user_access::create([
            'access_id' => Str::uuid(),
            'role_id' => 1,
            'nav_id' => $user_access
        ]);


        $user_list = Str::uuid();
        \App\Models\navigation::create([
            'nav_id' => Str::uuid(),
            'id_label' => 2,
            'url' => '/administrator/user',
            'navigation' => "User List",
            'icon' => 'user list',
            'is_active' => 1
        ]);

        \App\Models\user_access::create([
            'access_id' => Str::uuid(),
            'role_id' => 1,
            'nav_id' => $user_list
        ]);
    }
}
