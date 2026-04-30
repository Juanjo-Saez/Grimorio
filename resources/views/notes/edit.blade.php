@extends('layouts.app')
@section('title', 'Editar: '.$note->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h3 class="mb-4">Editar nota</h3>
                <form method="POST" action="{{ route('notes.update', $note) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Título *</label>
                        <input type="text" name="title" value="{{ old('title', $note->title) }}" class="form-control" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <input type="text" name="description" value="{{ old('description', $note->description) }}" class="form-control" maxlength="500">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contenido</label>
                        <textarea name="content" rows="12" class="form-control">{{ old('content', $note->content) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tags (separados por coma)</label>
                        @php $current = old('tags', $note->tags->pluck('name')->implode(', ')); @endphp
                        <input type="text" name="tags" value="{{ $current }}" class="form-control" list="userTagsList">
                        <datalist id="userTagsList">
                            @foreach($userTags as $tag)<option value="{{ $tag->name }}">@endforeach
                        </datalist>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary">Guardar cambios</button>
                        <a href="{{ route('notes.show', $note) }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
