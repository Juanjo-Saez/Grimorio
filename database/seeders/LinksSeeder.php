<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LinksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('links')->insert([
            'note_id' => 2,
            'read_only' => true,
            'expiry_date' => '2099-4-05 00:00:00',
            'shortURL' => "https://grimorio.es/shorturl",
        ]);
    }
}
