<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'seed test',
            'email' => 'seedtest@example.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'seed test 1',
            'email' => 'seedtest1@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
}

