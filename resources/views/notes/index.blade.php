@extends('layouts.app')
@section('title', 'Mis Notas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Mis Notas</h2>
    <a href="{{ route('notes.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nueva nota</a>
</div>

<form method="GET" action="{{ route('notes.index') }}" class="card shadow-sm mb-4" id="filterForm">
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-7">
                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar en título, contenido o descripción...">
            </div>
            <div class="col-md-2">
                <select name="op" class="form-select">
                    <option value="AND" @selected($op==='AND')>Todos los términos (AND)</option>
                    <option value="OR" @selected($op==='OR')>Cualquier término (OR)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Buscar</button>
                <a href="{{ route('notes.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>

        <div class="form-check form-switch mt-3">
            <input class="form-check-input" type="checkbox" name="shared" value="1" id="shared" @checked($shared) onchange="document.getElementById('filterForm').submit()">
            <label class="form-check-label" for="shared">Incluir notas compartidas conmigo</label>
        </div>

        @if($userTags->count())
            <div class="mt-3">
                <small class="text-muted d-block mb-2">Filtrar por tags:</small>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($userTags as $tag)
                        <label class="badge text-bg-light tag-chip @if(in_array($tag->id, $selectedTags)) active text-bg-primary @endif">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="d-none" @checked(in_array($tag->id, $selectedTags)) onchange="document.getElementById('filterForm').submit()">
                            #{{ $tag->name }}
                        </label>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</form>

@if($notes->count())
    <div class="row g-3">
        @foreach($notes as $note)
            <div class="col-md-6 col-lg-4">
                <div class="card note-card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="{{ route('notes.show', $note) }}" class="text-decoration-none">{{ $note->title }}</a>
                            @if(auth()->id() !== $note->user_id)
                                <span class="badge text-bg-info ms-1" title="Compartida">🔗</span>
                            @endif
                        </h5>
                        @if($note->description)
                            <p class="card-text text-muted small">{{ \Illuminate\Support\Str::limit($note->description, 120) }}</p>
                        @endif
                        @if($note->tags->count())
                            <div class="mb-2">
                                @foreach($note->tags as $tag)
                                    <span class="badge text-bg-secondary">#{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        @endif
                        <div class="mt-auto d-flex justify-content-between align-items-center pt-2 border-top">
                            <small class="text-muted">{{ $note->created_at->format('d/m/Y H:i') }}</small>
                            @if(auth()->id() === $note->user_id)
                                <div>
                                    <a href="{{ route('notes.edit', $note) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('notes.destroy', $note) }}" class="d-inline" onsubmit="return confirm('¿Eliminar nota?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $notes->links() }}</div>
@else
    <div class="text-center py-5 text-muted">
        <h4>📝 No hay notas</h4>
        <p>Crea tu primera nota para comenzar.</p>
        <a href="{{ route('notes.create') }}" class="btn btn-primary">Crear nota</a>
    </div>
@endif
@endsection
