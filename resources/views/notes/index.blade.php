@extends('layouts.app')
@section('title', 'Mis Notas - Grimorio')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
    <div>
        <h1>Mis Notas</h1>
        <p style="color: var(--text-secondary); margin-top: 0.5rem;">Organiza y explora tu colección de pensamientos</p>
    </div>
    <a href="{{ route('notes.create') }}" class="btn-primary">+ Nueva Nota</a>
</div>

<!-- Búsqueda y Filtros -->
<div class="card" style="margin-bottom: 2rem;  z-index: 10000;">
    <div class="card-body">
        <form method="GET" action="{{ route('notes.index') }}" style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label>Buscar</label>
                <input type="text" name="q" style="width: 100%;" placeholder="Título, contenido..." value="{{ $q }}">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label style="display: block; margin-bottom: 0.75rem;">Búsqueda</label>
                <div style="display: flex; gap: 0.5rem;">
                    <input type="hidden" name="op" id="op-input" value="{{ $op }}">
                    
                    <button type="button" class="toggle-btn" id="toggle-all" 
                        onclick="setOperator('AND')" 
                        style="flex: 1; padding: 0.75rem 1rem; border-radius: 12px; border: 2px solid var(--glass-border); background: {{ $op === 'AND' ? 'linear-gradient(135deg, var(--accent-gold), var(--accent-bronze))' : 'var(--glass-bg)' }}; color: {{ $op === 'AND' ? 'var(--primary-dark)' : 'var(--text-secondary)' }}; cursor: pointer; font-weight: 500; transition: all 0.3s ease; font-size: 0.95rem;">
                        Todas las palabras
                    </button>
                    
                    <button type="button" class="toggle-btn" id="toggle-any" 
                        onclick="setOperator('OR')" 
                        style="flex: 1; padding: 0.75rem 1rem; border-radius: 12px; border: 2px solid var(--glass-border); background: {{ $op === 'OR' ? 'linear-gradient(135deg, var(--accent-gold), var(--accent-bronze))' : 'var(--glass-bg)' }}; color: {{ $op === 'OR' ? 'var(--primary-dark)' : 'var(--text-secondary)' }}; cursor: pointer; font-weight: 500; transition: all 0.3s ease; font-size: 0.95rem;">
                        Cualquier palabra
                    </button>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0; position: relative;">
                <label style="display: block; margin-bottom: 0.75rem;">Tags</label>
                <div style="position: relative;">
                    <button type="button" id="tags-dropdown-btn" onclick="toggleTagsDropdown()" 
                        style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; border: 2px solid var(--glass-border); background: var(--glass-bg); color: var(--text-secondary); cursor: pointer; font-weight: 500; text-align: left; display: flex; justify-content: space-between; align-items: center;">
                        <span id="tags-display">{{ $selectedTags && count($selectedTags) > 0 ? count($selectedTags) . ' tags seleccionados' : 'Elige tags...' }}</span>
                        <span style="font-size: 1.2rem;">▼</span>
                    </button>
                    
                    <div id="tags-dropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: var(--glass-bg); border: 2px solid var(--glass-border); border-radius: 12px; margin-top: 0.5rem; max-height: 300px; overflow-y: auto; z-index: 10000; backdrop-filter: blur(20px);">
                        @if($userTags->count())
                            @foreach($userTags as $tag)
                                <label style="display: flex; align-items: center; padding: 0.75rem 1rem; cursor: pointer; border-bottom: 1px solid var(--glass-border); hover:background: rgba(212, 175, 55, 0.05); transition: all 0.2s ease;">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                        style="width: 18px; height: 18px; margin-right: 0.75rem; cursor: pointer; accent-color: var(--accent-gold);"
                                        {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }}
                                        onchange="updateTagsDisplay()">
                                    <span style="color: var(--text-secondary); font-weight: 500;">#{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        @else
                            <div style="padding: 1rem; color: var(--text-secondary); text-align: center; font-size: 0.9rem;">
                                Crea tags en tus notas para filtrar
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn-primary" style="padding: 0.75rem 1.5rem;">Filtrar</button>
                <a href="{{ route('notes.index') }}" class="btn-logout" style="padding: 0.75rem 1.5rem;">Limpiar</a>
            </div>
        </form>
    </div>
</div>

<!-- Grid de Notas -->
@if($notes->count())
    <div class="grid">
        @foreach($notes as $note)
            <a href="{{ route('notes.show', $note) }}" style="text-decoration: none; color: inherit;">
                <div class="card" style="height: 100%; cursor: pointer; display: flex; flex-direction: column;">
                    <div class="card-body" style="flex: 1; display: flex; flex-direction: column;">
                        <h3 style="margin-bottom: 0.5rem;">{{ $note->title }}</h3>
                        <p style="margin-bottom: 1rem; flex: 1; color: var(--text-secondary);">
                            {{ Str::limit($note->description ?? $note->content ?? '(Sin contenido)', 100) }}
                        </p>
                        
                        @if($note->tags->count())
                            <div style="margin-top: auto;">
                                @foreach($note->tags as $tag)
                                    <span class="badge">#{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        @endif
                        
                        <small style="color: var(--text-secondary); margin-top: 1rem; border-top: 1px solid var(--glass-border); padding-top: 1rem;">
                            {{ $note->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Paginación -->
    <div style="margin-top: 3rem; display: flex; justify-content: center;">
        {{ $notes->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="font-size: 1.2rem; color: var(--text-secondary);">No tienes notas aún</p>
        <a href="{{ route('notes.create') }}" class="btn-primary" style="margin-top: 1rem; display: inline-block;">Crear tu primera nota</a>
    </div>
@endif

<script>
function setOperator(op) {
    document.getElementById('op-input').value = op;
    
    const btnAll = document.getElementById('toggle-all');
    const btnAny = document.getElementById('toggle-any');
    
    if (op === 'AND') {
        btnAll.style.background = 'linear-gradient(135deg, var(--accent-gold), var(--accent-bronze))';
        btnAll.style.color = 'var(--primary-dark)';
        btnAll.style.borderColor = 'var(--accent-gold)';
        
        btnAny.style.background = 'var(--glass-bg)';
        btnAny.style.color = 'var(--text-secondary)';
        btnAny.style.borderColor = 'var(--glass-border)';
    } else {
        btnAll.style.background = 'var(--glass-bg)';
        btnAll.style.color = 'var(--text-secondary)';
        btnAll.style.borderColor = 'var(--glass-border)';
        
        btnAny.style.background = 'linear-gradient(135deg, var(--accent-gold), var(--accent-bronze))';
        btnAny.style.color = 'var(--primary-dark)';
        btnAny.style.borderColor = 'var(--accent-gold)';
    }
}

function toggleTagsDropdown() {
    const dropdown = document.getElementById('tags-dropdown');
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

function updateTagsDisplay() {
    const checkboxes = document.querySelectorAll('input[name="tags[]"]:checked');
    const btn = document.getElementById('tags-dropdown-btn');
    const display = document.getElementById('tags-display');
    
    if (checkboxes.length === 0) {
        display.textContent = 'Elige tags...';
    } else if (checkboxes.length === 1) {
        display.textContent = checkboxes[0].parentElement.textContent.trim();
    } else {
        display.textContent = `${checkboxes.length} tags seleccionados`;
    }
}

// Cerrar dropdown al clickear fuera
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('tags-dropdown');
    const btn = document.getElementById('tags-dropdown-btn');
    
    if (dropdown && btn && !dropdown.contains(event.target) && !btn.contains(event.target)) {
        dropdown.style.display = 'none';
    }
});

// Inicializar display al cargar
document.addEventListener('DOMContentLoaded', updateTagsDisplay);
</script>
@endsection
