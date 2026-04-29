@extends('layouts.app')

@section('title', 'Nueva Nota - Grimorio')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-5">
                <h2 class="card-title mb-4">Nueva Nota</h2>
                
                <form id="noteForm">
                    <div class="mb-3">
                        <label for="title" class="form-label">Título *</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Contenido</label>
                        <textarea class="form-control" id="content" name="content" rows="8"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <input type="text" class="form-control" id="description" name="description" maxlength="500" placeholder="Resumen de la nota">
                    </div>

                    <div class="mb-4">
                        <label for="tags" class="form-label">Tags (separados por coma)</label>
                        <input type="text" class="form-control" id="tags" name="tags" placeholder="trabajo, importante, personal">
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Guardar Nota</button>
                        <a href="/notes" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('extra-js')
<script>
    document.getElementById('noteForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        try {
            const noteData = {
                title: document.getElementById('title').value,
                content: document.getElementById('content').value,
                description: document.getElementById('description').value,
            };

            const response = await apiCall('/v1/notes', 'POST', noteData);
            
            alert('¡Nota creada exitosamente!');
            window.location.href = `/notes/${response.id}`;
        } catch (error) {
            console.error(error);
        }
    });

    if (!isAuthenticated()) {
        window.location.href = '/login';
    }
</script>
@endsection
@endsection
