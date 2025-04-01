<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'username' => "pepeleches",
            'email' => 'pepeleches@gmail.com',
            'password' => 1234,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'username' => "mariqueso",
            'email' => 'mari.quesos4@hotmail.com',
            'password' => "passw0rd!",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'username' => "qud",
            'email' => 'eureka@bañera.com',
            'password' => "lotengo!",
            'created_at' => now(),  
            'updated_at' => now(),  
        ]);
    }
}
