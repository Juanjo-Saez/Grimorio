<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Grimorio')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f4f6fb; min-height: 100vh; }
        .navbar-brand { font-weight: 700; }
        .note-card { transition: transform .15s, box-shadow .15s; }
        .note-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,.08); }
        .tag-chip { cursor: pointer; user-select: none; }
        .tag-chip.active { background: #0d6efd !important; color: #fff !important; }
        .content-pre { white-space: pre-wrap; word-wrap: break-word; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">📖 Grimorio</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="nav">
            @auth
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('notes.index') }}">Mis Notas</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('shared.index') }}">Compartidas</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('notes.create') }}">+ Nueva</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><span class="navbar-text me-3">{{ auth()->user()->email }}</span></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                            <button class="btn btn-outline-light btn-sm">Salir</button>
                        </form>
                    </li>
                </ul>
            @else
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Registro</a></li>
                </ul>
            @endauth
        </div>
    </div>
</nav>

<main class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error') || $errors->has('share'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') ?? $errors->first('share') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if($errors->any() && !$errors->has('share'))
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
