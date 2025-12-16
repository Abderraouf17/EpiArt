<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Test Customer',
            'email' => 'customer@epiart.com',
            'phone' => '0555123456',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);
    }
}
