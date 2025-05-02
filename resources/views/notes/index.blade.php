@extends('layouts.app')

@section('content')

    <div class="mb-6 text-center">
        <a href="{{ route('notes.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl font-semibold transition">
            ✨ Nueva Nota
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse ($notes as $note)
            <div class="bg-white border border-gray-200 rounded-xl shadow-md p-5 flex flex-col justify-between h-full">
                <div>
                    <h2 class="text-lg font-bold text-indigo-700 mb-2">
                        <a href="{{ route('notes.show', $note) }}" class="hover:underline">
                            {{ $note->filename }}
                        </a>
                    </h2>

                    <p class="text-gray-700 text-sm line-clamp-5 whitespace-pre-line">
                        {{ $notesContent[$loop->index] }}
                    </p>
                </div>

                <div class="mt-4 flex justify-end space-x-3 text-sm">
                    <a href="{{ route('notes.edit', $note) }}"
                       class="text-gray-500 hover:text-indigo-600 transition">Editar</a>

                    <form action="{{ route('notes.destroy', $note) }}" method="POST" onsubmit="return confirm('¿Eliminar esta nota?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-600 transition">Eliminar</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-white text-center col-span-full">No hay notas aún.</p>
        @endforelse
    </div>

@endsection

