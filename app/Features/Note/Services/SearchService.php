<?php

namespace App\Features\Note\Services;

use App\Models\Note;

class SearchService
{
    /**
     * Buscar notas por texto y/o tags
     */
    public function search($user, string $query = '', string $operator = 'AND', array $tagIds = [])
    {
        $userId = is_object($user) ? $user->id : $user;
        $useFulltext = \DB::getDriverName() === 'mysql';

        $builder = Note::where('user_id', $userId);

        // Búsqueda de texto
        if (!empty(trim($query))) {
            $searchTerms = $this->parseQuery($query);

            if ($operator === 'OR') {
                $builder->where(function ($q) use ($searchTerms, $useFulltext) {
                    foreach ($searchTerms as $term) {
                        if ($useFulltext) {
                            $q->orWhereRaw("MATCH(title, content, description) AGAINST(? IN BOOLEAN MODE)", [$term]);
                        } else {
                            $like = '%' . $term . '%';
                            $q->orWhere(function ($sub) use ($like) {
                                $sub->where('title', 'like', $like)
                                    ->orWhere('content', 'like', $like)
                                    ->orWhere('description', 'like', $like);
                            });
                        }
                    }
                });
            } else { // AND
                foreach ($searchTerms as $term) {
                    if ($useFulltext) {
                        $builder->whereRaw("MATCH(title, content, description) AGAINST(? IN BOOLEAN MODE)", [$term]);
                    } else {
                        $like = '%' . $term . '%';
                        $builder->where(function ($sub) use ($like) {
                            $sub->where('title', 'like', $like)
                                ->orWhere('content', 'like', $like)
                                ->orWhere('description', 'like', $like);
                        });
                    }
                }
            }
        }

        // Filtro por tags
        if (!empty($tagIds)) {
            $builder->whereHas('tags', function ($q) use ($tagIds) {
                $q->whereIn('tags.id', $tagIds);
            }, '=', count($tagIds));
        }

        return $builder->with('tags')
            ->latest()
            ->paginate(20);
    }

    /**
     * Parsear query con operadores AND/OR
     */
    public function parseQuery(string $query): array
    {
        $query = trim($query);
        
        // Detectar operadores
        if (preg_match('/\s(AND|OR)\s/i', $query)) {
            $terms = preg_split('/\s(AND|OR)\s/i', $query, -1, PREG_SPLIT_DELIM_CAPTURE);
            $result = [];
            
            foreach ($terms as $term) {
                if (!preg_match('/^(AND|OR)$/i', $term)) {
                    $result[] = trim($term);
                }
            }
            
            return array_filter($result);
        }

        // Si no hay operadores, retornar palabras
        return array_filter(preg_split('/\s+/', $query));
    }
}
