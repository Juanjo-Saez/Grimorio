<?php

namespace App\Features\Note\Services;

use App\Models\Note;
use App\Models\Tag;

class NoteService
{
    /**
     * Crear una nota
     */
    public function create($user, array $data): Note
    {
        $data['user_id'] = $user->id ?? $user;
        
        return Note::create($data);
    }

    /**
     * Obtener notas del usuario (lazy load)
     */
    public function getMyNotes($user, $page = 1, $perPage = 20)
    {
        $userId = is_object($user) ? $user->id : $user;
        
        return Note::where('user_id', $userId)
            ->with('tags')
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Obtener una nota específica
     */
    public function show($user, $noteId): ?Note
    {
        $userId = is_object($user) ? $user->id : $user;
        
        return Note::where('id', $noteId)
            ->where('user_id', $userId)
            ->with('tags')
            ->firstOrFail();
    }

    /**
     * Actualizar una nota
     */
    public function update($user, $noteId, array $data): Note
    {
        $userId = is_object($user) ? $user->id : $user;
        
        $note = Note::where('id', $noteId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $note->update($data);
        
        return $note->refresh()->load('tags');
    }

    /**
     * Eliminar una nota
     */
    public function delete($user, $noteId): bool
    {
        $userId = is_object($user) ? $user->id : $user;
        
        $note = Note::where('id', $noteId)
            ->where('user_id', $userId)
            ->firstOrFail();

        return $note->delete();
    }
}
