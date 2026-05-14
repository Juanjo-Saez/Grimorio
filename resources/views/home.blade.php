@extends('layouts.app')
@section('title', 'Grimorio - Zettelkasten Premium')

@section('content')
<!-- Hero Section -->
<div style="margin-bottom: 6rem;">
    <h1 style="font-size: 3.5rem; line-height: 1.1; margin-bottom: 1.5rem;">
        <i class="fas fa-book-open" style="margin-right: 0.5rem;"></i> Grimorio
    </h1>
    <p style="font-size: 1.2rem; color: var(--text-secondary); line-height: 1.8; margin-bottom: 2rem; max-width: 600px;">
        Una experiencia premium para centralizar tus pensamientos usando el método Zettelkasten. 
        <strong style="color: var(--accent-gold);">Captura, conecta, domina.</strong>
    </p>
    
    @guest
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 3rem;">
            <a href="{{ route('login') }}" class="btn-primary" style="padding: 1rem 2rem;">Acceder Ahora</a>
            <a href="{{ route('register') }}" class="btn-logout" style="padding: 1rem 2rem;">Crear Cuenta Gratuita</a>
        </div>
    @else
        <a href="{{ route('notes.index') }}" class="btn-primary" style="padding: 1rem 2rem; display: inline-block; margin-bottom: 3rem;">Ir a Mis Notas</a>
    @endguest

    <!-- Feature Cards en 3 columnas -->
    <div class="grid-3">
        <div class="card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(205, 127, 50, 0.05));">
            <div class="card-body">
                <h3 style="color: var(--accent-gold); margin-bottom: 0.5rem;">📝 Captura sin límites</h3>
                <p style="margin: 0; color: var(--text-secondary);">Notas con título, descripción, contenido y tags. Sin restricciones.</p>
            </div>
        </div>

        <div class="card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(205, 127, 50, 0.05));">
            <div class="card-body">
                <h3 style="color: var(--accent-gold); margin-bottom: 0.5rem;">🔍 Búsqueda inteligente</h3>
                <p style="margin: 0; color: var(--text-secondary);">Operadores AND/OR, filtros por tags, búsqueda avanzada.</p>
            </div>
        </div>

        <div class="card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(205, 127, 50, 0.05));">
            <div class="card-body">
                <h3 style="color: var(--accent-gold); margin-bottom: 0.5rem;">🤝 Colaboración segura</h3>
                <p style="margin: 0; color: var(--text-secondary);">Comparte con niveles de acceso: lectura o edición.</p>
            </div>
        </div>
    </div>
</div>

<!-- Divider -->
<div style="height: 1px; background: linear-gradient(90deg, transparent, var(--glass-border), transparent); margin: 6rem 0;"></div>

<!-- Benefits Section -->
<div style="text-align: center; margin-bottom: 4rem;">
    <h2 style="font-size: 2.2rem; margin-bottom: 3rem;">¿Por qué Grimorio?</h2>
    
    <div class="grid-3">
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">⚡</div>
                <h3>Ultra Rápido</h3>
                <p style="margin: 0; color: var(--text-secondary);">Interfaz fluida optimizada para productividad máxima.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🎯</div>
                <h3>Minimalista</h3>
                <p style="margin: 0; color: var(--text-secondary);">Solo lo esencial. Sin distracciones ni características innecesarias.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🔒</div>
                <h3>Privado y Seguro</h3>
                <p style="margin: 0; color: var(--text-secondary);">Tus notas son completamente tuyas. Control total.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🧠</div>
                <h3>Método Zettelkasten</h3>
                <p style="margin: 0; color: var(--text-secondary);">Sistema probado para capturar y conectar ideas.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🌐</div>
                <h3>Colaborativo</h3>
                <p style="margin: 0; color: var(--text-secondary);">Comparte conocimiento con tu equipo sin fricciones.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body" style="text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">✨</div>
                <h3>Premium Design</h3>
                <p style="margin: 0; color: var(--text-secondary);">Experiencia visual refinada e intuitiva.</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="card" style="text-align: center; background: linear-gradient(135deg, var(--accent-gold), var(--accent-bronze)); padding: 3rem;">
    <div class="card-body">
        <h2 style="color: var(--primary-dark); margin-bottom: 1rem;">Comienza tu viaje hacia el conocimiento organizado</h2>
        <p style="color: var(--primary-dark); font-size: 1.1rem; margin-bottom: 2rem;">
            Únete a otros usuarios que ya están transformando sus notas en conocimiento estructurado.
        </p>
        @guest
            <a href="{{ route('register') }}" class="btn-logout" style="padding: 1rem 2rem; color: var(--primary-dark); border-color: var(--primary-dark);">Crear Cuenta Gratuita</a>
        @else
            <a href="{{ route('notes.create') }}" class="btn-logout" style="padding: 1rem 2rem; color: var(--primary-dark); border-color: var(--primary-dark);">+ Nueva Nota</a>
        @endguest
    </div>
</div>

<!-- Footer info -->
<div style="text-align: center; margin-top: 6rem; padding-top: 3rem; border-top: 1px solid var(--glass-border); color: var(--text-secondary);">
    <p>
        Grimorio es una aplicación web opensource para notas tipo Zettelkasten. 
        <strong style="color: var(--accent-gold);">Tu conocimiento, tu control.</strong>
    </p>
</div>
@endsection
