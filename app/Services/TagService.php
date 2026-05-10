<?php

namespace App\Services;

use App\Models\Note;
use App\Models\Tag;
use App\Models\User;

class TagService
{
    public function getOrCreate(User $user, string $name): Tag
    {
        $name = strtoupper(trim($name));
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

    /**
     * Sugerir tags basados en palabras encontradas en el contenido de una nota.
     * Busca coincidencias case-insensitive entre palabras del contenido y nombres de tags.
     * 
     * @param User $user
     * @param string $title
     * @param string $content
     * @param string $description
     * @return array Array de tags sugeridos con estructura ['id' => int, 'name' => string]
     */
    public function suggestTagsFromContent(User $user, string $title = '', string $content = '', string $description = ''): array
    {
        $allTags = $this->getUserTags($user);
        
        // Combinar todo el contenido
        $fullText = strtolower(trim($title) . ' ' . trim($content) . ' ' . trim($description));
        
        if (empty($fullText)) {
            return [];
        }

        $suggested = [];

        foreach ($allTags as $tag) {
            $tagName = strtolower($tag->name);
            
            // Buscar el nombre del tag como palabra completa (para evitar falsos positivos)
            // Usamos límites de palabra \b en regex
            if (preg_match('/\b' . preg_quote($tagName, '/') . '\b/i', $fullText)) {
                $suggested[] = [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ];
            }
        }

        return $suggested;
    }
}
