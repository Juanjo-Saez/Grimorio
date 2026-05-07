<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_notes_index(): void
    {
        $this->get('/notes')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_list_their_notes(): void
    {
        $user = User::factory()->create();
        Note::factory()->for($user)->count(3)->create();

        $response = $this->actingAs($user)->get('/notes');

        $response->assertOk();
        $response->assertViewIs('notes.index');
    }

    public function test_user_can_create_a_note(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/notes', [
            'title' => 'Mi primera nota',
            'description' => 'Resumen breve',
            'content' => 'Contenido de prueba',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('notes', [
            'user_id' => $user->id,
            'title' => 'Mi primera nota',
        ]);
    }

    public function test_user_cannot_create_two_notes_with_same_title(): void
    {
        $user = User::factory()->create();
        Note::factory()->for($user)->create(['title' => 'Repetido']);

        $response = $this->actingAs($user)->post('/notes', [
            'title' => 'Repetido',
            'content' => 'Otro contenido',
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_user_cannot_view_another_users_note(): void
    {
        $alice = User::factory()->create();
        $bob = User::factory()->create();
        $note = Note::factory()->for($alice)->create();

        $this->actingAs($bob)->get("/notes/{$note->id}")->assertNotFound();
    }

    public function test_user_can_update_their_note(): void
    {
        $user = User::factory()->create();
        $note = Note::factory()->for($user)->create(['title' => 'Original']);

        $this->actingAs($user)->put("/notes/{$note->id}", [
            'title' => 'Actualizado',
            'content' => 'Nuevo contenido',
        ])->assertRedirect();

        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'title' => 'Actualizado',
        ]);
    }

    public function test_user_can_delete_their_note(): void
    {
        $user = User::factory()->create();
        $note = Note::factory()->for($user)->create();

        $this->actingAs($user)->delete("/notes/{$note->id}")->assertRedirect('/notes');
        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }
}
