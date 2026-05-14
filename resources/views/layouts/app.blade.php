<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Grimorio')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-dark: #0a0e27;
            --primary-light: #1a1f3a;
            --accent-gold: #d4af37;
            --accent-platinum: #e8e8e8;
            --accent-bronze: #cd7f32;
            --text-primary: #e8e8e8;
            --text-secondary: #a0a0a0;
            --glass-bg: rgba(26, 31, 58, 0.8);
            --glass-border: rgba(212, 175, 55, 0.2);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-light) 100%);
            color: var(--text-primary);
            min-height: 100vh;
            backdrop-filter: blur(1px);
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(212, 175, 55, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(205, 127, 50, 0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        main { position: relative; z-index: 1; }

        nav {
            background: linear-gradient(180deg, rgba(10, 14, 39, 0.95) 0%, rgba(26, 31, 58, 0.9) 100%);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent-gold) 0%, var(--accent-platinum) 50%, var(--accent-bronze) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            transition: transform 0.3s ease, filter 0.3s ease;
            letter-spacing: 2px;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
            filter: drop-shadow(0 0 15px rgba(212, 175, 55, 0.3));
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
            align-items: center;
        }

        .nav-link {
            font-weight: 500;
            font-size: 0.95rem;
            color: var(--text-secondary);
            text-decoration: none;
            position: relative;
            transition: color 0.3s ease;
            letter-spacing: 0.5px;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--accent-gold), var(--accent-bronze));
            transition: width 0.3s ease;
        }

        .nav-link:hover {
            color: var(--accent-gold);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-user {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .user-email {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .btn-primary, .btn-logout {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-gold) 0%, var(--accent-bronze) 100%);
            color: var(--primary-dark);
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 25px 50px rgba(212, 175, 55, 0.35);
        }

        .btn-logout {
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--glass-border);
        }

        .btn-logout:hover {
            background: rgba(212, 175, 55, 0.1);
            color: var(--accent-gold);
            border-color: var(--accent-gold);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }

        .alert {
            background: rgba(26, 31, 58, 0.9);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            animation: slideDown 0.3s ease;
        }

        .alert-success {
            border-color: rgba(52, 211, 153, 0.3);
            background: linear-gradient(135deg, rgba(26, 31, 58, 0.9), rgba(52, 211, 153, 0.05));
            color: #34d399;
        }

        .alert-danger {
            border-color: rgba(248, 113, 113, 0.3);
            background: linear-gradient(135deg, rgba(26, 31, 58, 0.9), rgba(248, 113, 113, 0.05));
            color: #f87171;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-close { filter: brightness(1.5); cursor: pointer; }

        .card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            backdrop-filter: blur(20px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            overflow: visible;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--accent-gold), transparent);
            opacity: 0.5;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 40px 80px rgba(212, 175, 55, 0.15);
            border-color: rgba(212, 175, 55, 0.4);
        }

        .card-body { padding: 2rem; }

        input, textarea, select {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            color: var(--text-primary);
            padding: 0.75rem 1rem;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.3s ease;
        }

        input::placeholder, textarea::placeholder {
            color: var(--text-secondary);
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--accent-gold);
            background: rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.2);
        }

        label {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-group { margin-bottom: 1.5rem; }

        .badge {
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-bronze));
            color: var(--primary-dark);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .badge:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3);
        }

        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
            font-weight: 800;
            letter-spacing: 1px;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-platinum));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        h2 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        h3 {
            font-size: 1.4rem;
            margin-bottom: 0.8rem;
            color: var(--text-primary);
        }

        p { line-height: 1.6; color: var(--text-secondary); margin-bottom: 1rem; }

        .content-pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            background: rgba(0, 0, 0, 0.2);
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 3px solid var(--accent-gold);
            font-family: 'Courier New', monospace;
            font-size: 0.95rem;
            line-height: 1.6;
            color: var(--text-primary);
        }

        .grid {
            display: grid;
            gap: 2rem;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }

        .grid-2 { 
            display: grid;
            gap: 2rem;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); 
        }
        
        .grid-3 { 
            display: grid;
            gap: 2rem;
            grid-template-columns: repeat(3, 1fr); 
        }

        .mt-4 { margin-top: 2rem; }
        .mb-4 { margin-bottom: 2rem; }
        .gap-2 { gap: 1rem; }
        .gap-3 { gap: 1.5rem; }
        .text-center { text-align: center; }
        .d-flex { display: flex; }
        .flex-wrap { flex-wrap: wrap; }
        .justify-between { justify-content: space-between; }
        .align-center { align-items: center; }

        @media (max-width: 768px) {
            .container { padding: 2rem 1rem; }
            h1 { font-size: 1.8rem; }
            h2 { font-size: 1.4rem; }
            .nav-links { gap: 1rem; font-size: 0.85rem; }
            .navbar-brand { font-size: 1.4rem; }
            .card-body { padding: 1.5rem; }
            .grid-2, .grid, .grid-3 { grid-template-columns: 1fr; }
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>
<nav>
    <div class="navbar-container">
        <a href="{{ route('home') }}" class="navbar-brand"><i class="fas fa-book-magic" style="margin-right: 0.5rem;"></i> GRIMORIO</a>
        <ul class="nav-links">
            @auth
                <li><a href="{{ route('notes.index') }}" class="nav-link">Mis Notas</a></li>
                <li><a href="{{ route('shared.index') }}" class="nav-link">Compartidas</a></li>
                <li><a href="{{ route('notes.create') }}" class="nav-link">+ Nueva</a></li>
            @else
                <li><a href="{{ route('login') }}" class="nav-link">Acceso</a></li>
                <li><a href="{{ route('register') }}" class="nav-link">Registro</a></li>
            @endauth
        </ul>
        @auth
            <div class="nav-user">
                <span class="user-email">{{ auth()->user()->email }}</span>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-logout">Salir</button>
                </form>
            </div>
        @endauth
    </div>
</nav>

<main>
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                <button type="button" class="btn-close" onclick="this.parentElement.style.display='none';">×</button>
            </div>
        @endif
        
        @if(session('error') || $errors->has('share'))
            <div class="alert alert-danger">
                {{ session('error') ?? $errors->first('share') }}
                <button type="button" class="btn-close" onclick="this.parentElement.style.display='none';">×</button>
            </div>
        @endif
        
        @if($errors->any() && !$errors->has('share'))
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</main>

<script>
    const cards = document.querySelectorAll('.card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });
</script>

@stack('scripts')
</body>
</html>
