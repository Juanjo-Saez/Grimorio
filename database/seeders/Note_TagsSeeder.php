<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Note_TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('note_tags')->insert([
            'note_id' => 1,
            'tag_id' => 2,
        ]);
        DB::table('note_tags')->insert([
            'note_id' => 2,
            'tag_id' => 1,
        ]);
        DB::table('note_tags')->insert([
            'note_id' => 2,
            'tag_id' => 3,
        ]);
    }
}
