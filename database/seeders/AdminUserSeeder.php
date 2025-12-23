<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Use raw SQL to ignore duplicates directly at the database level
        // This bypasses any Eloquent weirdness or strict mode issues
        \Illuminate\Support\Facades\DB::statement("
            INSERT INTO users (name, email, password, is_admin, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
            ON CONFLICT (email) DO NOTHING
        ", ['Admin', 'admin@epiart.local', Hash::make('admin123'), true]);

        \Illuminate\Support\Facades\DB::statement("
            INSERT INTO users (name, email, password, is_admin, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
            ON CONFLICT (email) DO NOTHING
        ", ['Demo Customer', 'customer@epiart.local', Hash::make('customer123'), false]);

        echo "✅ Seeding completed safely.\n";
    }
}
