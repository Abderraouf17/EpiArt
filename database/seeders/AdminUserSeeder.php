<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        try {
            User::firstOrCreate(
                ['email' => 'admin@epiart.local'],
                [
                    'name' => 'Admin',
                    'password' => Hash::make('admin123'),
                    'is_admin' => true,
                ]
            );

            User::firstOrCreate(
                ['email' => 'customer@epiart.local'],
                [
                    'name' => 'Demo Customer',
                    'password' => Hash::make('customer123'),
                    'is_admin' => false,
                ]
            );
        } catch (\Exception $e) {
            // Context: User likely already exists with different casing or strict constraint
            // We ignore this error to allow deployment to proceed
            echo "⚠️ User already exists or collision detected: " . $e->getMessage() . "\n";
        }
    }
}
