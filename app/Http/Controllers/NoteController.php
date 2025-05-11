<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class NoteController extends Controller
{

    public function index()
    {
        $notes = Auth::user()->notes()->latest()->get();
        $notesContent= [];
        foreach ($notes as $note) {
            $path = 'userNotes/' . Auth::user()->username . '/' . $note->filename . '.md';
            array_push($notesContent,Storage::get($path));
        }
        return view('notes.index', compact('notes', 'notesContent'));
    }


    public function create()
    {
        return view('notes.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'filename' => 'required|string|max:255|unique:notes,filename',
        ]);
        Note::create([
            'filename' => $request->filename,
            'user_id' => Auth::id(),
        ]);

        $folder = 'userNotes/' . Auth::user()->username;
        $path = "$folder/{$request->filename}.md";

        Storage::put($path, $request->content);

        return redirect()->route('notes.index');
    }


    public function edit(Note $note)
    {
        if (auth()->id() !== $note->user_id) {
            abort(403, 'No tienes permiso para editar esta nota.');
        }

        $folder = 'userNotes/' . Auth::user()->username;
        $path = "$folder/{$note->filename}.md";
    
        $content = Storage::exists($path) ? Storage::get($path) : '';

        return view('notes.edit', compact('note', 'content'));
    }


    public function update(Request $request, Note $note)
    {

        if (auth()->id() !== $note->user_id) {
            abort(403, 'No tienes permiso para editar esta nota.');
        }
    
        $request->validate([
            'filename' => 'required|string|max:255',
        ]);
    
        $note->update([
            'filename' => $request->input('filename'),
        ]);
    
        $folder = 'userNotes/' . Auth::user()->username;
        $path = "$folder/{$note->filename}.md";

        Storage::put($path, $request->content);

        return redirect()->route('notes.index');
    }
    

    public function destroy(Note $note)
    {
        if (auth()->id() !== $note->user_id) {
            abort(403, 'No tienes permiso para eliminar esta nota.');
        }
    
        $note->delete();
    
        $folder = 'userNotes/' . Auth::user()->username;
        $path = "$folder/{$note->filename}.md";

        Storage::delete($path);

        return redirect()->route('notes.index');
    }
    


}
