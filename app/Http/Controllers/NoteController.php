<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = Note::where('user_id', Auth::id())->get(); 
        // Obtiene todas las notas (ajusta esto si tienes filtros o condiciones)
        return view('notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar el input
        $request->validate([
            'filename' => 'required|string|max:255|unique:notes,filename',
        ]);
        // Crear la nota con el usuario autenticado
        Note::create([
            'filename' => $request->filename,
            'user_id' => Auth::id(),
        ]);

    
        // Redirigir con mensaje
        return redirect()->route('notes.index')->with('success', 'Nota creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        return view('notes.show',compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        if (auth()->id() !== $note->user_id) {
            abort(403, 'No tienes permiso para editar esta nota.');
        }

        return view('notes.edit', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        // Verificar que el usuario autenticado sea el dueño de la nota
        if (auth()->id() !== $note->user_id) {
            abort(403, 'No tienes permiso para editar esta nota.');
        }
    
        // Validación
        $request->validate([
            'filename' => 'required|string|max:255',
        ]);
    
        // Actualizar la nota
        $note->update([
            'filename' => $request->input('filename'),
        ]);
    
        // Redirigir con mensaje
        return redirect()->route('notes.index')->with('success', 'Nota actualizada correctamente.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        // Verificar que el usuario autenticado sea el dueño de la nota
        if (auth()->id() !== $note->user_id) {
            abort(403, 'No tienes permiso para eliminar esta nota.');
        }
    
        $note->delete();
    
        return redirect()->route('notes.index')->with('success', 'Nota eliminada correctamente.');
    }
    
}
