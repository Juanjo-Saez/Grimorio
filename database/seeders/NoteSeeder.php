<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear 20 notas distribuidas entre usuarios existentes
        $users = User::all();

        if ($users->isEmpty()) {
            // Si no hay usuarios, crearlos primero
            $this->call(UserSeeder::class);
            $users = User::all();
        }

        // Crear notas para cada usuario
        foreach ($users as $user) {
            Note::factory(4)->create(['user_id' => $user->id]);
        }
    }
}
