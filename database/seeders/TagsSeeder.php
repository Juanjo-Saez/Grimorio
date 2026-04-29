<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tags')->insert([
            'user_id' => 1,
            'tagname' => "Programing",
        ]);
        DB::table('tags')->insert([
            'user_id' => 1,
            'tagname' => "Learning",
        ]);
        DB::table('tags')->insert([
            'user_id' => 2,
            'tagname' => "Magic",
        ]);
        DB::table('tags')->insert([
            'user_id' => 2,
            'tagname' => "Spells",
        ]);
    }
}
