<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

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
            'password' => Hash::make(1234),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->createUserFolder('pepeleches');

        DB::table('users')->insert([
            'username' => "mariqueso",
            'email' => 'mari.quesos4@hotmail.com',
            'password' => Hash::make("passw0rd!"),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->createUserFolder('mariqueso');

        DB::table('users')->insert([
            'username' => "qud",
            'email' => 'eureka@bañera.com',
            'password' => Hash::make("lotengo!"),
            'created_at' => now(),  
            'updated_at' => now(),  
        ]);
        $this->createUserFolder('qud');
    }

    private function createUserFolder(string $username) {
        $path = storage_path('app/userNotes/'.$username);
        File::makeDirectory($path, 0755, true);
    }
}
