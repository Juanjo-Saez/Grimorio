@extends('layouts.app')

@section('title', 'Login - Grimorio')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body p-5">
                <h2 class="card-title mb-4 text-center">Acceso al Grimorio</h2>
                
                <form id="loginForm">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-3">Ingresar</button>
                </form>

                <hr>
                <p class="text-center mb-0">
                    ¿No tienes cuenta? <a href="/register">Regístrate aquí</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        try {
            const response = await apiCall('/auth/login', 'POST', {
                email: document.getElementById('email').value,
                password: document.getElementById('password').value
            });
            
            // Guardar token
            localStorage.setItem('token', response.token);
            localStorage.setItem('user', JSON.stringify(response.user));
            
            // Redirigir
            window.location.href = '/notes';
        } catch (error) {
            console.error(error);
        }
    });
</script>
@endsection

