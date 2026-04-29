<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            'title' => "Pepe Leches First Note",
            'content' => "Esta es la primera nota de prueba de Pepe Leches.",
            'description' => "Nota de prueba",
            'created_at' => now(),  
            'updated_at' => now(),  
        ]);

        DB::table('notes')->insert([
            'user_id' => 2,
            'title' => "Fireball Spell",
            'content' => "A powerful fire-based spell. Pues...no se...fireball?",
            'description' => "Magic spell documentation",
            'created_at' => now(),  
            'updated_at' => now(),  
        ]);

        DB::table('notes')->insert([
            'user_id' => 1,
            'title' => "Learning Notes",
            'content' => "Notes about learning programming and best practices.",
            'description' => "Educational content",
            'created_at' => now(),  
            'updated_at' => now(),  
        ]);
    }

}
