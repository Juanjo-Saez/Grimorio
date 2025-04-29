@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('notes.store') }}" class="space-y-6">
        @csrf

        <div>
            <label for="filename" class="block text-sm font-semibold text-gray-700">Título</label>
            <input
                id="filename"
                name="filename"
                type="text"
                placeholder="Título de la nota"
                required
                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
            @error('filename')
                <div class="text-sm text-red-500 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button
            type="submit"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-xl transition-all duration-200 shadow-md"
        >
            Guardar Nota
        </button>
    </form>
@endsection
