@extends('layouts.app')
@section('title', $note->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h2 class="mb-1">{{ $note->title }}</h2>
                        <small class="text-muted">Compartida por {{ $link->owner->email }} ·
                            <span class="badge text-bg-{{ $link->access_level === 'edit' ? 'warning' : 'info' }}">{{ $link->access_level }}</span>
                        </small>
                    </div>
                    <a href="{{ route('shared.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
                </div>

                @if($note->tags->count())
                    <div class="mb-3">
                        @foreach($note->tags as $tag)<span class="badge text-bg-secondary">#{{ $tag->name }}</span>@endforeach
                    </div>
                @endif

                <hr>

                @if($link->access_level === 'edit')
                    <form method="POST" action="{{ route('shared.update', $link->token) }}">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <input type="text" name="description" value="{{ old('description', $note->description) }}" class="form-control" maxlength="500">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contenido</label>
                            <textarea name="content" rows="14" class="form-control">{{ old('content', $note->content) }}</textarea>
                        </div>
                        <div class="alert alert-info small">El título solo lo puede editar el propietario.</div>
                        <button class="btn btn-primary">Guardar cambios</button>
                    </form>
                @else
                    @if($note->description)
                        <p class="text-muted">{{ $note->description }}</p>
                    @endif
                    <div class="content-pre">{{ $note->content ?? '(Sin contenido)' }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
