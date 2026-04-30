@extends('layouts.app')
@section('title', 'Grimorio - Tu Zettelkasten personal')

@section('content')
<div class="row align-items-center py-5">
    <div class="col-lg-7">
        <h1 class="display-4 fw-bold mb-3">📖 Grimorio</h1>
        <p class="lead text-muted">Una webapp <strong>rápida, pragmática y flexible</strong> para centralizar tus notas con el método Zettelkasten.</p>
        <p>Captura ideas sueltas, asóciales tags, busca por palabras clave, refina tu conocimiento y compártelo con otros usuarios.</p>
        @guest
            <div class="mt-4">
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-2">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">Crear cuenta</a>
            </div>
        @else
            <a href="{{ route('notes.index') }}" class="btn btn-primary btn-lg mt-4">Ir a mis notas</a>
        @endguest
    </div>
    <div class="col-lg-5">
        <div class="row g-3">
            <div class="col-12"><div class="card border-0 shadow-sm"><div class="card-body"><h5>📝 Crea</h5><p class="mb-0 text-muted small">Notas con título, contenido y tags. Sin fricción.</p></div></div></div>
            <div class="col-12"><div class="card border-0 shadow-sm"><div class="card-body"><h5>🔍 Busca</h5><p class="mb-0 text-muted small">Operadores AND/OR y filtros por tags.</p></div></div></div>
            <div class="col-12"><div class="card border-0 shadow-sm"><div class="card-body"><h5>🔗 Comparte</h5><p class="mb-0 text-muted small">Permisos de lectura o edición por nota.</p></div></div></div>
        </div>
    </div>
</div>
@endsection
