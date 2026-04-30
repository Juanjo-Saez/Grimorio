<?php

namespace App\Services;

use App\Models\Note;
use App\Models\Tag;
use App\Models\User;

class TagService
{
    public function getOrCreate(User $user, string $name): Tag
    {
        $name = trim($name);
        return Tag::firstOrCreate([
            'user_id' => $user->id,
            'name' => $name,
        ]);
    }

    public function attachToNote(Note $note, Tag $tag): void
    {
        $note->tags()->syncWithoutDetaching([$tag->id]);
    }

    public function detachFromNote(Note $note, Tag $tag): void
    {
        $note->tags()->detach($tag->id);
    }

    /**
     * Sincroniza todos los tags de una nota a partir de un array de nombres.
     */
    public function syncFromNames(User $user, Note $note, array $names): void
    {
        $tagIds = [];
        foreach ($names as $name) {
            $name = trim($name);
            if ($name === '') {
                continue;
            }
            $tagIds[] = $this->getOrCreate($user, $name)->id;
        }
        $note->tags()->sync($tagIds);
    }

    public function getUserTags(User $user)
    {
        return $user->tags()->orderBy('name')->get();
    }

    /**
     * Obtener notas del usuario que tienen un tag dado (para mostrar al crear una nota).
     */
    public function getNotesByTag(User $user, int $tagId, int $limit = 5)
    {
        return $user->notes()
            ->whereHas('tags', fn ($q) => $q->where('tags.id', $tagId))
            ->latest()
            ->limit($limit)
            ->get(['id', 'title', 'description', 'created_at']);
    }
}
