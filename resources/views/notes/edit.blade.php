@extends('layouts.app')

@section('content')

    <script>
        function generateLink(noteId) {
            const options ={
                method: 'POST'
            }
            fetch('link', options)
        }

    </script>

        <form method="POST" action="{{ route('notes.update', $note) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="filename" class="block text-sm font-semibold text-gray-700">Título</label>
                <input
                    id="filename"
                    name="filename"
                    type="text"
                    value="{{ old('filename', $note->filename) }}"
                    required
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                @error('filename')
                    <div class="text-sm text-red-500 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="content" class="block text-sm font-semibold text-gray-700">Contenido</label>
                <textarea
                    id="content"
                    name="content"
                    rows="4"
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >{{ old('content', $content) }}</textarea>
                @error('content')
                    <div class="text-sm text-red-500 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button
                onclick="generateLink({{ $note->id }})"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-xl shadow-md transition-all duration-200"
            >
                Compartir
            </button>

            <button
                type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-xl transition-all duration-200 shadow-md"
            >
                Actualizar Nota
            </button>

        </form>
        <form action="{{ route('notes.destroy', $note->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta nota?');">
        @csrf
        @method('DELETE')
        <button
            type="submit"
            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 mt-2 px-4 rounded-xl shadow-md transition-all duration-200"
        >
            Eliminar
        </button>
        </form>

    <a href="{{ route('notes.index') }}"
        class="inline-block mt-6 text-indigo-600 hover:text-indigo-800 underline">
        ← Volver al Grimorio
    </a>
@endsection
