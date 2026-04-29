@extends('layouts.app')

@section('title', 'Grimorio - Tu Zettelkasten personal')

@section('content')
<div class="container mt-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-8">
            <h1 class="display-4 mb-4">📓 Bienvenido a Grimorio</h1>
            <p class="lead text-muted mb-4">
                Tu aplicación personal de notas estilo Zettelkasten. Organiza, busca y comparte tus ideas con facilidad.
            </p>
            <div class="d-flex gap-3">
                <a href="/login" class="btn btn-primary btn-lg">Iniciar Sesión</a>
                <a href="/register" class="btn btn-outline-primary btn-lg">Crear Cuenta</a>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <div style="font-size: 8rem; opacity: 0.5;">📔</div>
        </div>
    </div>

    <hr class="my-5">

    <div class="row mt-5">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h3 class="card-title">✍️ Crea</h3>
                    <p class="card-text">Crea notas con título, contenido y descripción. Organízalas con tags personalizados.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h3 class="card-title">🔍 Busca</h3>
                    <p class="card-text">Busca por texto con operadores AND/OR. Filtra por tags para encontrar exactamente lo que necesitas.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h3 class="card-title">🔗 Comparte</h3>
                    <p class="card-text">Comparte tus notas con otros usuarios. Controla los permisos de lectura y edición.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <h3 class="mb-4">Características principales</h3>
            <ul class="list-unstyled">
                <li class="mb-3"><strong>CRUD completo:</strong> Crear, leer, actualizar y eliminar notas fácilmente.</li>
                <li class="mb-3"><strong>Búsqueda avanzada:</strong> AND, OR y filtros por tags.</li>
                <li class="mb-3"><strong>Tags organizados:</strong> Categoriza tus notas por temas.</li>
                <li class="mb-3"><strong>Compartición de notas:</strong> Comparte con otros usuarios con control de permisos.</li>
                <li class="mb-3"><strong>Interfaz intuitiva:</strong> Diseño limpio y fácil de usar.</li>
                <li class="mb-3"><strong>API REST:</strong> Acceso a través de API para integraciones.</li>
            </ul>
        </div>
    </div>
</div>

<footer class="text-center mt-5 py-5 border-top">
    <small class="text-muted">Grimorio © 2026 - Tu cuaderno de hechizos digitales</small>
</footer>
@endsection
