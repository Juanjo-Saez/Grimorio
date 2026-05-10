@extends('layouts.app')
@section('title', $note->title . ' (Compartida) - Grimorio')

@section('content')
<a href="{{ route('shared.index') }}" style="color: var(--accent-gold); text-decoration: none; font-weight: 600; display: inline-block; margin-bottom: 2rem;">← Volver a compartidas</a>

<div class="grid-2" style="grid-template-columns: 2fr 1fr;">
    <div>
        <!-- Encabezado y Contenido -->
        <div class="card" style="margin-bottom: 2rem;">
            <div class="card-body">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
                    <div style="flex: 1;">
                        <h1 style="margin-bottom: 0.5rem;">{{ $note->title }}</h1>
                        @if($note->description)
                            <p style="font-size: 1.1rem; color: var(--text-secondary);">{{ $note->description }}</p>
                        @endif
                        <small style="color: var(--text-secondary);">
                            Creada {{ $note->created_at->format('d/m/Y H:i') }} · Actualizada {{ $note->updated_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                </div>

                @if($note->tags->count())
                    <div style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid var(--glass-border);">
                        @foreach($note->tags as $tag)
                            <span class="badge">#{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="content-pre">{{ $note->content ?? '(Sin contenido)' }}</div>

                @if(isset($access_level) && $access_level === 'edit')
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--glass-border);">
                        <form method="POST" action="{{ route('shared.update', $token) }}">
                            @csrf @method('PUT')

                            <h3 style="margin-bottom: 1rem;">Editar Contenido</h3>

                            <div class="form-group">
                                <label for="description">Descripción</label>
                                <textarea id="description" name="description" style="width: 100%; height: 80px;">{{ $note->description }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="content">Contenido</label>
                                <textarea id="content" name="content" style="width: 100%; height: 300px; font-family: 'Courier New', monospace;">{{ $note->content }}</textarea>
                            </div>

                            <button type="submit" class="btn-primary" style="padding: 0.75rem 1.5rem;">Guardar cambios</button>
                        </form>
                    </div>
                @else
                    <div style="background: rgba(212, 175, 55, 0.1); padding: 1rem; border-radius: 8px; border-left: 3px solid var(--accent-gold); margin-top: 2rem;">
                        <small style="color: var(--text-secondary);">
                            <strong style="color: var(--accent-gold);"> Lectura</strong> — Esta nota te ha sido compartida en modo lectura. No puedes editarla.
                        </small>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <div class="card">
            <div class="card-body">
                <h3 style="margin-bottom: 1rem;">Información de compartición</h3>
                <div style="display: grid; gap: 1rem;">
                    <div style="border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem;">
                        <small style="color: var(--text-secondary); display: block;">Propietario</small>
                        <p style="margin: 0; color: var(--text-primary);">{{ $note->user->email }}</p>
                    </div>
                    <div style="border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem;">
                        <small style="color: var(--text-secondary); display: block;">Nivel de acceso</small>
                        <p style="margin: 0; color: var(--accent-gold); font-weight: 600;">
                            {{ isset($access_level) && $access_level === 'edit' ? 'Edición (puedes modificar)' : 'Lectura (solo ver)' }}
                        </p>
                    </div>
                    <div>
                        <small style="color: var(--text-secondary); display: block;">Compartido</small>
                        <p style="margin: 0; color: var(--text-primary);">{{ $link->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
