<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Services\NoteService;
use App\Services\SearchService;
use App\Services\TagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function __construct(
        protected NoteService $notes,
        protected SearchService $search,
        protected TagService $tags,
    ) {}

    public function index(Request $request)
    {
        $user = Auth::user();
        $q = $request->string('q')->toString();
        $op = $request->string('op')->toString() ?: 'AND';
        $tagIds = array_filter((array) $request->input('tags', []));
        $shared = $request->boolean('shared');

        $hasFilters = $q !== '' || !empty($tagIds) || $shared;

        $notes = $hasFilters
            ? $this->search->search($user, $q, $op, $tagIds, $shared)
            : $this->notes->index($user);

        $userTags = $this->tags->getUserTags($user);

        return view('notes.index', [
            'notes' => $notes,
            'userTags' => $userTags,
            'q' => $q,
            'op' => $op,
            'selectedTags' => array_map('intval', $tagIds),
            'shared' => $shared,
        ]);
    }

    public function create()
    {
        return view('notes.create', [
            'userTags' => $this->tags->getUserTags(Auth::user()),
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'tags' => ['nullable', 'string', 'max:500'],
        ]);

        // Validar título único por usuario
        $request->validate([
            'title' => function ($attr, $value, $fail) use ($user) {
                if ($user->notes()->where('title', $value)->exists()) {
                    $fail('Ya tienes una nota con ese título.');
                }
            },
        ]);

        $note = $this->notes->create($user, $data);

        if (!empty($data['tags'])) {
            $names = array_filter(array_map('trim', explode(',', $data['tags'])));
            $this->tags->syncFromNames($user, $note, $names);
        }

        return redirect()->route('notes.show', $note)->with('success', 'Nota creada correctamente.');
    }

    public function show(Note $note)
    {
        $note = $this->notes->show(Auth::user(), $note);
        return view('notes.show', compact('note'));
    }

    public function edit(Note $note)
    {
        $note = $this->notes->show(Auth::user(), $note);
        $userTags = $this->tags->getUserTags(Auth::user());
        return view('notes.edit', compact('note', 'userTags'));
    }

    public function update(Request $request, Note $note)
    {
        $user = Auth::user();
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'tags' => ['nullable', 'string', 'max:500'],
        ]);

        // Validar título único por usuario excepto esta nota
        $request->validate([
            'title' => function ($attr, $value, $fail) use ($user, $note) {
                if ($user->notes()->where('title', $value)->where('id', '!=', $note->id)->exists()) {
                    $fail('Ya tienes otra nota con ese título.');
                }
            },
        ]);

        $note = $this->notes->update($user, $note, $data);

        $names = !empty($data['tags'])
            ? array_filter(array_map('trim', explode(',', $data['tags'])))
            : [];
        $this->tags->syncFromNames($user, $note, $names);

        return redirect()->route('notes.show', $note)->with('success', 'Nota actualizada.');
    }

    public function destroy(Note $note)
    {
        $this->notes->delete(Auth::user(), $note);
        return redirect()->route('notes.index')->with('success', 'Nota eliminada.');
    }

    /**
     * AJAX: notas relacionadas con un tag (para vista crear nota).
     */
    public function notesByTag(Request $request, int $tag)
    {
        $notes = $this->tags->getNotesByTag(Auth::user(), $tag);
        return response()->json($notes);
    }

    /**
     * AJAX: tags del usuario (para autocomplete).
     */
    public function userTags()
    {
        return response()->json($this->tags->getUserTags(Auth::user()));
    }
}
