<?php

namespace App\Services;

use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NoteService
{
    public function index(User $user, int $perPage = 10)
    {
        return $user->notes()
            ->with('tags')
            ->latest()
            ->paginate($perPage);
    }

    public function create(User $user, array $data): Note
    {
        return $user->notes()->create([
            'title' => $data['title'],
            'content' => $data['content'] ?? null,
            'description' => $data['description'] ?? null,
        ]);
    }

    public function show(User $user, Note $note): Note
    {
        $this->ensureOwner($user, $note);
        return $note->load('tags', 'sharedLinks.recipient');
    }

    public function update(User $user, Note $note, array $data): Note
    {
        $this->ensureOwner($user, $note);
        $note->update([
            'title' => $data['title'],
            'content' => $data['content'] ?? null,
            'description' => $data['description'] ?? null,
        ]);
        return $note->refresh();
    }

    public function delete(User $user, Note $note): void
    {
        $this->ensureOwner($user, $note);
        $note->delete();
    }

    protected function ensureOwner(User $user, Note $note): void
    {
        if ($note->user_id !== $user->id) {
            throw new ModelNotFoundException('Nota no encontrada');
        }
    }
}
