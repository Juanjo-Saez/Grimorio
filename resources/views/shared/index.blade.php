@extends('layouts.app')
@section('title', 'Compartidas conmigo')

@section('content')
<h2 class="mb-4">Compartidas conmigo</h2>

@if($shares->count())
    <div class="list-group shadow-sm">
        @foreach($shares as $sl)
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">{{ $sl->note->title }}</h6>
                    <small class="text-muted">De {{ $sl->owner->email }} · {{ $sl->created_at?->format('d/m/Y H:i') }}</small>
                    <span class="badge text-bg-{{ $sl->access_level === 'edit' ? 'warning' : 'info' }} ms-2">{{ $sl->access_level }}</span>
                </div>
                <a href="{{ route('shared.show', $sl->token) }}" class="btn btn-sm btn-primary">{{ $sl->access_level === 'edit' ? 'Ver / Editar' : 'Ver' }}</a>
            </div>
        @endforeach
    </div>
    <div class="mt-3">{{ $shares->links() }}</div>
@else
    <div class="text-center py-5 text-muted">
        <h5>📭 Nadie ha compartido notas contigo aún</h5>
    </div>
@endif
@endsection
