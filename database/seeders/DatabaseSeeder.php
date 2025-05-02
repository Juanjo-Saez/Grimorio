<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UsersSeeder::class);
        $this->call(NotesSeeder::class);
        $this->call(LinksSeeder::class);
        $this->call(TagsSeeder::class);
        $this->call(Note_TagsSeeder::class);
    }
}
