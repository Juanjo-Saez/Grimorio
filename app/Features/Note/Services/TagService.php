<?php

namespace App\Features\Note\Services;

use App\Models\Tag;
use App\Models\Note;

class TagService
{
    /**
     * Obtener o crear un tag
     */
    public function getOrCreate($user, string $tagName): Tag
    {
        $userId = is_object($user) ? $user->id : $user;
        
        return Tag::firstOrCreate(
            ['user_id' => $userId, 'tagname' => $tagName],
            ['user_id' => $userId, 'tagname' => $tagName]
        );
    }

    /**
     * Asignar tag a nota (evitar duplicados)
     */
    public function attachToNote(Note $note, Tag $tag)
    {
        // Evitar duplicados
        $note->tags()->syncWithoutDetaching([$tag->id]);
        
        return $note->load('tags');
    }

    /**
     * Obtener tags del usuario
     */
    public function getUserTags($user)
    {
        $userId = is_object($user) ? $user->id : $user;
        
        return Tag::where('user_id', $userId)->get();
    }
}
