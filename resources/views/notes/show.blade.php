@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <h2 class="text-2xl font-bold text-indigo-700">Detalle de la Nota</h2>

        <div class="bg-white rounded-xl p-6 shadow-md">
            <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $note->filename }}</h3>
            <p class="text-gray-700 whitespace-pre-line">{{ $note->content }}</p>

            <div class="text-sm text-gray-500 mt-4">
                Creado el: {{ $note->created_at->format('d/m/Y H:i') }}
            </div>
        </div>

        @auth
            <div class="flex space-x-4 mt-4">
                <a href="{{ route('notes.edit', $note->id) }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-xl shadow-md transition-all duration-200">
                    Editar
                </a>

                <form action="{{ route('notes.destroy', $note->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta nota?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-xl shadow-md transition-all duration-200">
                        Eliminar
                    </button>
                </form>
            </div>
        @endauth

        <a href="{{ route('notes.index') }}"
           class="inline-block mt-6 text-indigo-600 hover:text-indigo-800 underline">
            ← Volver al Grimorio
        </a>
    </div>
@endsection
