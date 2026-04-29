<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'email' => 'test2@example.com',
            'password_hash' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

