@extends('layouts.app')
@section('content')

    <div class="mb-6 text-center">
        <a href="{{ route('notes.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl font-semibold transition">
            Nueva Nota
        </a>
    </div>

    @forelse ($notes as $note)

        <div class="mb-4 p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
            <h2 class="text-xl font-bold text-gray-800">
                <a href="{{ route('notes.show', $note) }}" class="hover:underline">{{ $note->filename }}</a>
            </h2>

            <p>{{ $notesContent[$loop->index] }}</p>
            <div class="mt-3 flex gap-2">
                <a href="{{ route('notes.edit', $note) }}"
                   class="text-indigo-600 hover:underline text-sm font-semibold">Editar</a>

                <form action="{{ route('notes.destroy', $note) }}" method="POST" onsubmit="return confirm('¿Eliminar esta nota?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:underline text-sm font-semibold">Eliminar</button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-gray-600 text-center">No hay notas aún.</p>
    @endforelse
@endsection
