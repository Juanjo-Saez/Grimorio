<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Grimorio')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #667eea;
            --dark: #1a202c;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: var(--primary) !important;
            font-size: 1.5rem;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .card {
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .note-card {
            cursor: pointer;
            border-left: 4px solid var(--primary);
        }

        .container-main {
            padding: 40px 20px;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .loading {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }

        .spinner-border {
            color: var(--primary);
        }

        h1, h2, h3 {
            color: var(--dark);
        }

        .badge-primary {
            background: var(--primary);
        }

        .modal-content {
            border-radius: 10px;
            border: none;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }
    </style>
    @yield('extra-css')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="/">📓 Grimorio</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item" id="auth-links">
                        <a class="nav-link" href="/login">Login</a>
                        <a class="nav-link" href="/register">Registro</a>
                    </li>
                    <li class="nav-item" id="user-links" style="display: none;">
                        <a class="nav-link" href="/notes">Mis Notas</a>
                        <a class="nav-link" href="/notes/create">+ Nueva</a>
                        <a class="nav-link" href="/" onclick="logout()">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="loading" id="loading">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>

    <div class="container-main">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_URL = 'http://localhost:8000/api';
        
        // Verificar si está autenticado
        function isAuthenticated() {
            return !!localStorage.getItem('token');
        }

        // Actualizar navbar
        function updateNavbar() {
            const authLinks = document.getElementById('auth-links');
            const userLinks = document.getElementById('user-links');
            
            if (isAuthenticated()) {
                authLinks.style.display = 'none';
                userLinks.style.display = 'block';
            } else {
                authLinks.style.display = 'block';
                userLinks.style.display = 'none';
            }
        }

        // Logout
        function logout() {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            window.location.href = '/login';
        }

        // Mostrar loading
        function showLoading() {
            document.getElementById('loading').style.display = 'block';
        }

        // Ocultar loading
        function hideLoading() {
            document.getElementById('loading').style.display = 'none';
        }

        // Hacer request con token
        async function apiCall(endpoint, method = 'GET', data = null) {
            showLoading();
            try {
                const options = {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                };

                const token = localStorage.getItem('token');
                if (token) {
                    options.headers['Authorization'] = `Bearer ${token}`;
                }

                if (data) {
                    options.body = JSON.stringify(data);
                }

                const response = await fetch(`${API_URL}${endpoint}`, options);
                const json = await response.json();

                if (!response.ok) {
                    let msg = json.error || json.message || `Error ${response.status}`;
                    if (json.errors) {
                        const detalle = Object.values(json.errors).flat().join(' | ');
                        if (detalle) msg = detalle;
                    }
                    throw new Error(msg);
                }

                return json;
            } catch (error) {
                alert('Error: ' + error.message);
                throw error;
            } finally {
                hideLoading();
            }
        }

        // Inicializar navbar
        updateNavbar();
    </script>
    @yield('extra-js')
</body>
</html>