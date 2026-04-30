@extends('layouts.app')
@section('title', 'Nueva nota')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h3 class="mb-4">Nueva nota</h3>
                <form method="POST" action="{{ route('notes.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Título *</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <input type="text" name="description" value="{{ old('description') }}" class="form-control" maxlength="500" placeholder="Resumen breve">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contenido</label>
                        <textarea name="content" rows="10" class="form-control">{{ old('content') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tags (separados por coma)</label>
                        <input type="text" name="tags" id="tagsInput" value="{{ old('tags') }}" class="form-control" placeholder="javascript, productividad" list="userTagsList">
                        <datalist id="userTagsList">
                            @foreach($userTags as $tag)
                                <option value="{{ $tag->name }}">
                            @endforeach
                        </datalist>
                        <small class="text-muted">Al añadir un tag existente verás notas relacionadas a la derecha.</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary">Guardar</button>
                        <a href="{{ route('notes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm sticky-top" style="top:1rem">
            <div class="card-body">
                <h6 class="card-title">Notas relacionadas</h6>
                <div id="related" class="text-muted small">Escribe un tag para ver notas relacionadas.</div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const userTags = @json($userTags->pluck('id', 'name'));
const input = document.getElementById('tagsInput');
const related = document.getElementById('related');

async function refreshRelated() {
    const names = input.value.split(',').map(s => s.trim()).filter(Boolean);
    const matched = names.map(n => userTags[n]).filter(Boolean);
    if (!matched.length) { related.innerHTML = 'Escribe un tag para ver notas relacionadas.'; return; }

    const all = [];
    for (const id of matched) {
        const r = await fetch(`/api/notes/by-tag/${id}`, {headers:{'Accept':'application/json'}});
        if (r.ok) all.push(...await r.json());
    }
    if (!all.length) { related.innerHTML = '<em>Sin notas previas con ese tag.</em>'; return; }

    const seen = new Set();
    const unique = all.filter(n => !seen.has(n.id) && seen.add(n.id));
    related.innerHTML = unique.slice(0,5).map(n =>
        `<div class="border-bottom py-2"><a href="/notes/${n.id}" target="_blank">${n.title}</a><br><small class="text-muted">${n.description || ''}</small></div>`
    ).join('');
}

input.addEventListener('input', () => { clearTimeout(window._t); window._t = setTimeout(refreshRelated, 400); });
</script>
@endpush
@endsection
