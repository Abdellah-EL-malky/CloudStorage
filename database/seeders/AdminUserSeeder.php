<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Création de l'admin
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@cloudstorage.com',
            'password' => Hash::make('password'),
            'storage_limit' => 10737418240, // 10GB
        ]);

        // Assignation de l'admin
        $adminRole = Role::where('name', 'admin')->first();
        $user->roles()->attach($adminRole);
    }
}
