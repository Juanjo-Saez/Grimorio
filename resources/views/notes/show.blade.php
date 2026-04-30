@extends('layouts.app')
@section('title', $note->title)

@section('content')
<div class="row">
    <div class="col-lg-9">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h2 class="mb-1">{{ $note->title }}</h2>
                        @if($note->description)<p class="text-muted mb-0">{{ $note->description }}</p>@endif
                        <small class="text-muted">Creada {{ $note->created_at->format('d/m/Y H:i') }} · Actualizada {{ $note->updated_at->format('d/m/Y H:i') }}</small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('notes.edit', $note) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i> Editar</a>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#shareModal"><i class="bi bi-share"></i> Compartir</button>
                        <form method="POST" action="{{ route('notes.destroy', $note) }}" onsubmit="return confirm('¿Eliminar nota?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger"><i class="bi bi-trash"></i> Eliminar</button>
                        </form>
                    </div>
                </div>

                @if($note->tags->count())
                    <div class="mb-3">
                        @foreach($note->tags as $tag)
                            <span class="badge text-bg-secondary">#{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @endif

                <hr>
                <div class="content-pre">{{ $note->content ?? '(Sin contenido)' }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Compartida con</h6>
                @forelse($note->sharedLinks as $sl)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2 small">
                        <div>
                            {{ $sl->recipient->email }}<br>
                            <span class="badge text-bg-{{ $sl->access_level === 'edit' ? 'warning' : 'info' }}">{{ $sl->access_level }}</span>
                        </div>
                        <form method="POST" action="{{ route('shared.destroy', $sl) }}" onsubmit="return confirm('¿Revocar acceso?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x"></i></button>
                        </form>
                    </div>
                @empty
                    <p class="text-muted small mb-0">No la has compartido aún.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('shared.store', $note) }}" class="modal-content">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Compartir nota</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Email del destinatario</label>
                    <input type="email" name="recipient_email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label d-block">Permisos</label>
                    <div class="form-check form-check-inline"><input type="radio" name="access_level" value="read" class="form-check-input" id="r1" checked><label class="form-check-label" for="r1">Solo lectura</label></div>
                    <div class="form-check form-check-inline"><input type="radio" name="access_level" value="edit" class="form-check-input" id="r2"><label class="form-check-label" for="r2">Lectura y edición</label></div>
                </div>
            </div>
            <div class="modal-footer"><button class="btn btn-primary">Compartir</button></div>
        </form>
    </div>
</div>
@endsection
