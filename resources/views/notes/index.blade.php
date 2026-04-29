@extends('layouts.app')

@section('title', 'Mis Notas - Grimorio')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Mis Notas</h1>
        </div>
        <div class="col-md-4">
            <a href="/notes/create" class="btn btn-primary w-100">+ Nueva Nota</a>
        </div>
    </div>

    <!-- Búsqueda -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" id="searchQuery" class="form-control" placeholder="Buscar notas...">
                </div>
                <div class="col-md-3">
                    <select id="searchOp" class="form-control">
                        <option value="AND">AND (Todos)</option>
                        <option value="OR">OR (Cualquiera)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-primary w-100" onclick="search()">🔍 Buscar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Notas -->
    <div id="notesList"></div>

    <!-- Estado vacío -->
    <div id="emptyState" class="empty-state" style="display: none;">
        <div class="empty-state-icon">📝</div>
        <h3>No hay notas aún</h3>
        <p>Crea tu primera nota para comenzar</p>
        <a href="/notes/create" class="btn btn-primary">Crear Primera Nota</a>
    </div>
</div>

@section('extra-js')
<script>
    let notes = [];

    async function loadNotes() {
        try {
            const response = await apiCall('/v1/notes');
            notes = response.data || [];
            renderNotes(notes);
        } catch (error) {
            console.error(error);
        }
    }

    async function search() {
        const query = document.getElementById('searchQuery').value;
        const op = document.getElementById('searchOp').value;
        
        if (!query.trim()) {
            loadNotes();
            return;
        }

        try {
            const response = await apiCall(`/v1/notes/search?q=${encodeURIComponent(query)}&op=${op}`);
            notes = response.data || [];
            renderNotes(notes);
        } catch (error) {
            console.error(error);
        }
    }

    function renderNotes(notesList) {
        const container = document.getElementById('notesList');
        const emptyState = document.getElementById('emptyState');

        if (notesList.length === 0) {
            container.innerHTML = '';
            emptyState.style.display = 'block';
            return;
        }

        emptyState.style.display = 'none';
        container.innerHTML = notesList.map(note => `
            <div class="card note-card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <h5 class="card-title">${note.title || 'Sin título'}</h5>
                            <p class="card-text text-muted">${note.description || 'Sin descripción'}</p>
                            ${note.tags && note.tags.length > 0 ? `
                                <div>
                                    ${note.tags.map(tag => `<span class="badge badge-primary">${tag.name}</span>`).join(' ')}
                                </div>
                            ` : ''}
                            <small class="text-muted">Creada: ${new Date(note.created_at).toLocaleDateString('es-ES')}</small>
                        </div>
                        <div class="col-md-3 text-end">
                            <a href="/notes/${note.id}" class="btn btn-sm btn-outline-primary mb-1">Ver</a>
                            <a href="/notes/${note.id}/edit" class="btn btn-sm btn-outline-warning mb-1">Editar</a>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteNote(${note.id})">Eliminar</button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    async function deleteNote(id) {
        if (!confirm('¿Eliminar esta nota?')) return;

        try {
            await apiCall(`/v1/notes/${id}`, 'DELETE');
            alert('Nota eliminada');
            loadNotes();
        } catch (error) {
            console.error(error);
        }
    }

    // Cargar notas al iniciar
    if (isAuthenticated()) {
        loadNotes();
    } else {
        window.location.href = '/login';
    }
</script>
@endsection
@endsection

