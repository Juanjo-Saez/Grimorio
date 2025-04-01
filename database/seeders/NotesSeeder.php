<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('notes')->insert([
            'user_id' => 1,
            'filename' => "pepe-leches-1",
            'created_at' => now(),  
            'updated_at' => now(),  
        ]);
        DB::table('notes')->insert([
            'user_id' => 3,
            'filename' => "fireball",
            'created_at' => now(),  
            'updated_at' => now(),  
        ]);
    }
}
