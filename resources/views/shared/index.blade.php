@extends('layouts.app')
@section('title', 'Notas Compartidas - Grimorio')

@section('content')
<div style="margin-bottom: 3rem;">
    <h1>Notas Compartidas Conmigo</h1>
    <p style="color: var(--text-secondary); margin-top: 0.5rem;">Accede a las notas que otros usuarios han compartido contigo</p>
</div>

@if($shares->count())
    <div class="grid">
        @foreach($shares as $sl)
            <a href="{{ route('shared.show', $sl->token) }}" style="text-decoration: none; color: inherit;">
                <div class="card" style="height: 100%; cursor: pointer;">
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                            <h3 style="margin: 0;">{{ $sl->note->title }}</h3>
                            <span class="badge" style="background: {{ $sl->access_level === 'edit' ? 'linear-gradient(135deg, #fbbf24, #f59e0b)' : 'linear-gradient(135deg, #60a5fa, #3b82f6)' }};">
                                {{ ucfirst($sl->access_level) }}
                            </span>
                        </div>

                        <p style="margin-bottom: 1rem; flex: 1; color: var(--text-secondary);">
                            {{ Str::limit($sl->note->description ?? $sl->note->content ?? '(Sin contenido)', 100) }}
                        </p>

                        <small style="color: var(--text-secondary); border-top: 1px solid var(--glass-border); padding-top: 1rem; display: block;">
                            De <strong style="color: var(--accent-gold);">{{ $sl->owner->email }}</strong>
                            <br>
                            Compartido {{ $sl->created_at?->format('d/m/Y H:i') }}
                        </small>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Paginación -->
    <div style="margin-top: 3rem; display: flex; justify-content: center;">
        {{ $shares->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="font-size: 1.5rem; color: var(--text-secondary);">📭 Nadie ha compartido notas contigo aún</p>
        <p style="color: var(--text-secondary); margin-top: 1rem;">
            Cuando alguien comparta una nota contigo, aparecerá aquí
        </p>
    </div>
@endif
@endsection
