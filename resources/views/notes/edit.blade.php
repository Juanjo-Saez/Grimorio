@extends('layouts.app')

@section('title', 'Editar Nota - Grimorio')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-5">
                <h2 class="card-title mb-4">Editar Nota</h2>
                
                <form id="editForm">
                    <div class="mb-3">
                        <label for="title" class="form-label">Título *</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Contenido</label>
                        <textarea class="form-control" id="content" name="content" rows="8"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Descripción</label>
                        <input type="text" class="form-control" id="description" name="description" maxlength="500">
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <a href="#" class="btn btn-secondary" onclick="window.history.back()">Cancelar</a>
                    </div>
                </form>
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
            document.getElementById('title').value = note.title;
            document.getElementById('content').value = note.content || '';
            document.getElementById('description').value = note.description || '';
        } catch (error) {
            console.error(error);
            alert('No se pudo cargar la nota');
        }
    }

    document.getElementById('editForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        try {
            await apiCall(`/v1/notes/${noteId}`, 'PUT', {
                title: document.getElementById('title').value,
                content: document.getElementById('content').value,
                description: document.getElementById('description').value,
            });

            alert('¡Nota actualizada!');
            window.location.href = `/notes/${noteId}`;
        } catch (error) {
            console.error(error);
        }
    });

    if (isAuthenticated()) {
        loadNote();
    } else {
        window.location.href = '/login';
    }
</script>
@endsection
@endsection
