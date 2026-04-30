<?php

namespace App\Services;

use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class SearchService
{
    /**
     * Buscar notas del usuario combinando texto, operador AND/OR y tags.
     * Si $includeShared es true, incluye también notas compartidas con el usuario.
     */
    public function search(
        User $user,
        ?string $query = null,
        string $operator = 'AND',
        array $tagIds = [],
        bool $includeShared = false,
        int $perPage = 10
    ) {
        $operator = strtoupper($operator) === 'OR' ? 'OR' : 'AND';
        $terms = $this->parseQuery($query ?? '');

        $builder = Note::query()->with('tags', 'user');

        // Restringir a notas propias o compartidas con el usuario
        $builder->where(function (Builder $q) use ($user, $includeShared) {
            $q->where('user_id', $user->id);
            if ($includeShared) {
                $q->orWhereIn('id', function ($sub) use ($user) {
                    $sub->select('note_id')
                        ->from('shared_links')
                        ->where('recipient_id', $user->id);
                });
            }
        });

        // Filtro de texto (LIKE en title, content, description)
        if (!empty($terms)) {
            $builder->where(function (Builder $q) use ($terms, $operator) {
                foreach ($terms as $term) {
                    $like = '%' . $term . '%';
                    if ($operator === 'OR') {
                        $q->orWhere(function (Builder $sub) use ($like) {
                            $sub->where('title', 'LIKE', $like)
                                ->orWhere('content', 'LIKE', $like)
                                ->orWhere('description', 'LIKE', $like);
                        });
                    } else {
                        $q->where(function (Builder $sub) use ($like) {
                            $sub->where('title', 'LIKE', $like)
                                ->orWhere('content', 'LIKE', $like)
                                ->orWhere('description', 'LIKE', $like);
                        });
                    }
                }
            });
        }

        // Filtro de tags (AND: todas las tags requeridas)
        if (!empty($tagIds)) {
            foreach ($tagIds as $tagId) {
                $builder->whereHas('tags', fn ($q) => $q->where('tags.id', $tagId));
            }
        }

        return $builder->latest()->paginate($perPage)->withQueryString();
    }

    public function parseQuery(string $query): array
    {
        $query = trim($query);
        if ($query === '') {
            return [];
        }

        // Si hay operadores explícitos AND/OR los limpiamos como separadores
        if (preg_match('/\s+(AND|OR)\s+/i', $query)) {
            $parts = preg_split('/\s+(AND|OR)\s+/i', $query);
        } else {
            $parts = preg_split('/\s+/', $query);
        }

        return array_values(array_filter(array_map('trim', $parts), fn ($t) => $t !== ''));
    }
}
