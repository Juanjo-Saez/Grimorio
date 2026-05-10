@extends('layouts.app')
@section('title', $note->title . ' - Grimorio')

@section('content')
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
                    
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; justify-content: flex-end;">
                        <a href="{{ route('notes.edit', $note) }}" class="btn-primary" style=" text-decoration:none; padding: 0.75rem 1.25rem;">Editar</a>
                        <button type="button" class="btn-primary" style="padding: 0.75rem 1.25rem; background: linear-gradient(135deg, var(--accent-gold), var(--accent-bronze)); color: var(--primary-dark);" onclick="document.getElementById('shareModal').style.display='flex'">Compartir</button>
                        <form method="POST" action="{{ route('notes.destroy', $note) }}" style="display: inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-primary" style="padding: 0.75rem 1.25rem; background: linear-gradient(135deg, rgba(248, 113, 113, 0.8), rgba(244, 67, 54, 0.8)); color: white;" onclick="return confirm('¿Eliminar esta nota permanentemente?')">Eliminar</button>
                        </form>
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
            </div>
        </div>
    </div>

    <!-- Sidebar: Información y acciones -->
    <div>
        <!-- Estadísticas -->
        <div class="card" style="margin-bottom: 2rem;">
            <div class="card-body">
                <h3 style="margin-bottom: 1rem;">Detalles</h3>
                <div style="display: grid; gap: 1rem;">
                    <div style="border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem;">
                        <small style="color: var(--text-secondary); display: block;">Propietario</small>
                        <p style="margin: 0; color: var(--text-primary);">{{ $note->user->email }}</p>
                    </div>
                    <div style="border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem;">
                        <small style="color: var(--text-secondary); display: block;">Creada</small>
                        <p style="margin: 0; color: var(--text-primary);">{{ $note->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <small style="color: var(--text-secondary); display: block;">Actualizada</small>
                        <p style="margin: 0; color: var(--text-primary);">{{ $note->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Links de compartición activos -->
        @if($note->sharedLinks->count())
            <div class="card">
                <div class="card-body">
                    <h3 style="margin-bottom: 1rem;">Compartida con</h3>
                    @foreach($note->sharedLinks as $link)
                        <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--glass-border);">
                            <p style="margin: 0; color: var(--text-primary);">{{ $link->recipient->email }}</p>
                            <small style="color: var(--text-secondary);">
                                Acceso: <strong>{{ ucfirst($link->access_level) }}</strong>
                            </small>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal de compartición -->
<div id="shareModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 500px;">
        <div class="card-body">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Compartir Nota</h2>
                <button onclick="document.getElementById('shareModal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; color: var(--text-secondary); cursor: pointer;">×</button>
            </div>

            <form method="POST" action="{{ route('shared.store', $note) }}">
                @csrf
                
                <div class="form-group">
                    <label>Email del destinatario</label>
                    <input type="email" name="recipient_email" style="width: 100%;" required>
                    @error('recipient_email') <small style="color: #f87171;">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label>Nivel de acceso</label>
                    <select name="access_level" style="width: 100%;">
                        <option value="read">Lectura (solo ver)</option>
                        <option value="edit">Edición (modificar contenido)</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; padding: 1rem;">Compartir</button>
            </form>

            @error('share') <p style="color: #f87171; margin-top: 1rem;">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

@endsection
