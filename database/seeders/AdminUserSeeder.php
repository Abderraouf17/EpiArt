<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@epiart.local',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
        ]);

        User::create([
            'name' => 'Demo Customer',
            'email' => 'customer@epiart.local',
            'password' => Hash::make('customer123'),
            'is_admin' => false,
        ]);
    }
}
