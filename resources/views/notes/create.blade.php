@extends('layouts.app')
@section('title', 'Nueva Nota - Grimorio')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <h1 style="margin-bottom: 3rem;">Crear Nueva Nota</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('notes.store') }}">
                @csrf

                <div class="form-group">
                    <label for="title">Título *</label>
                    <input type="text" id="title" name="title" style="width: 100%; font-size: 1.3rem; font-weight: 600;" placeholder="Dale un nombre memorable" value="{{ old('title') }}" required>
                    @error('title') <small style="color: #f87171;">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea id="description" name="description" style="width: 100%; height: 100px; font-size: 1rem; resize: vertical;" placeholder="Un resumen breve de la nota...">{{ old('description') }}</textarea>
                    @error('description') <small style="color: #f87171;">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="content">Contenido</label>
                    <textarea id="content" name="content" style="width: 100%; height: 400px; font-size: 1rem; resize: vertical; font-family: 'Courier New', monospace;" placeholder="Escribe tu contenido aquí...">{{ old('content') }}</textarea>
                    @error('content') <small style="color: #f87171;">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="tags">Tags</label>
                    <input type="text" id="tags" name="tags" style="width: 100%;" placeholder="Separa con comas: trabajo, importante, urgente..." value="{{ old('tags') }}">
                    <small style="color: var(--text-secondary); display: block; margin-top: 0.5rem;">Escribe tags separados por comas para categorizar tu nota</small>
                    @error('tags') <small style="color: #f87171;">{{ $message }}</small> @enderror
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn-primary" style="padding: 1rem 2rem;">Crear Nota</button>
                    <a href="{{ route('notes.index') }}" class="btn-logout" style="padding: 1rem 2rem;">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
