<?php

namespace App\Features\Note\Controllers;

use App\Http\Controllers\Controller;
use App\Features\Note\Services\NoteService;
use App\Features\Note\Services\SearchService;
use App\Features\Note\Services\TagService;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    protected $noteService;
    protected $searchService;
    protected $tagService;

    public function __construct(
        NoteService $noteService,
        SearchService $searchService,
        TagService $tagService
    ) {
        $this->noteService = $noteService;
        $this->searchService = $searchService;
        $this->tagService = $tagService;
    }

    /**
     * Listar notas
     */
    public function index(Request $request)
    {
        $user = auth()->user() ?? auth('api')->user() ?? (object)['id' => 1];
        $page = $request->get('page', 1);
        
        $notes = $this->noteService->getMyNotes($user, $page);
        
        return response()->json($notes);
    }

    /**
     * Crear nota
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'description' => 'nullable|string|max:500',
        ]);

        $user = auth()->user() ?? auth('api')->user() ?? (object)['id' => 1];
        
        $note = $this->noteService->create($user, $validated);
        
        return response()->json($note, 201);
    }

    /**
     * Ver nota
     */
    public function show($id)
    {
        $user = auth()->user() ?? auth('api')->user() ?? (object)['id' => 1];
        
        try {
            $note = $this->noteService->show($user, $id);
            return response()->json($note);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Not found'], 404);
        }
    }

    /**
     * Actualizar nota
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'description' => 'nullable|string|max:500',
        ]);

        $user = auth()->user() ?? auth('api')->user() ?? (object)['id' => 1];
        
        try {
            $note = $this->noteService->update($user, $id, $validated);
            return response()->json($note);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Not found'], 404);
        }
    }

    /**
     * Eliminar nota
     */
    public function destroy($id)
    {
        $user = auth()->user() ?? auth('api')->user() ?? (object)['id' => 1];
        
        try {
            $this->noteService->delete($user, $id);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Not found'], 404);
        }
    }

    /**
     * Buscar notas
     */
    public function search(Request $request)
    {
        $user = auth()->user() ?? auth('api')->user() ?? (object)['id' => 1];
        
        $query = $request->get('q', '');
        $operator = $request->get('op', 'AND');
        $tags = $request->get('tags', []);

        if (is_string($tags)) {
            $tags = array_filter(explode(',', $tags));
        }

        $notes = $this->searchService->search($user, $query, $operator, $tags);
        
        return response()->json($notes);
    }
}
