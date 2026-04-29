@extends('layouts.app')

@section('title', 'Ver Nota - Grimorio')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-5" id="noteContent">
                <p class="text-center">Cargando...</p>
            </div>
        </div>
    </div>
</div>

@section('extra-js')
<script>
    const noteId = window.location.pathname.split('/')[2];

    async function loadNote() {
        try {
            const note = await apiCall(`/v1/notes/${noteId}`);
            renderNote(note);
        } catch (error) {
            console.error(error);
            document.getElementById('noteContent').innerHTML = '<p class="alert alert-danger">Nota no encontrada</p>';
        }
    }

    function renderNote(note) {
        document.getElementById('noteContent').innerHTML = `
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h2 class="mb-2">${note.title}</h2>
                        ${note.description ? `<p class="text-muted">${note.description}</p>` : ''}
                        <small class="text-muted">Creada: ${new Date(note.created_at).toLocaleString('es-ES')}</small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="/notes/${note.id}/edit" class="btn btn-outline-warning">Editar</a>
                        <button class="btn btn-outline-danger" onclick="deleteAndRedirect(${note.id})">Eliminar</button>
                    </div>
                </div>

                ${note.tags && note.tags.length > 0 ? `
                    <div class="mb-4">
                        ${note.tags.map(tag => `<span class="badge badge-primary">${tag.name}</span>`).join(' ')}
                    </div>
                ` : ''}

                <hr>
                <div class="mt-4">
                    <h5>Contenido</h5>
                    <p style="white-space: pre-wrap;">${note.content || '(Sin contenido)'}</p>
                </div>
            </div>
        `;
    }

    async function deleteAndRedirect(id) {
        if (!confirm('¿Eliminar esta nota?')) return;
        
        try {
            await apiCall(`/v1/notes/${id}`, 'DELETE');
            alert('Nota eliminada');
            window.location.href = '/notes';
        } catch (error) {
            console.error(error);
        }
    }

    if (isAuthenticated()) {
        loadNote();
    } else {
        window.location.href = '/login';
    }
</script>
@endsection
@endsection
