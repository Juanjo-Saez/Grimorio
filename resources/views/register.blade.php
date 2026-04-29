@extends('layouts.app')

@section('title', 'Registro - Grimorio')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body p-5">
                <h2 class="card-title mb-4 text-center">Crear Cuenta</h2>
                
                <form id="registerForm">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                        <small class="form-text text-muted">Mínimo 8 caracteres</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-3">Crear Cuenta</button>
                </form>

                <hr>
                <p class="text-center mb-0">
                    ¿Ya tienes cuenta? <a href="/login">Inicia sesión</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('registerForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirmation').value;
        
        if (password !== passwordConfirm) {
            alert('Las contraseñas no coinciden');
            return;
        }
        
        try {
            await apiCall('/auth/register', 'POST', {
                email: document.getElementById('email').value,
                password: password,
                password_confirmation: passwordConfirm
            });
            
            alert('¡Registro exitoso! Ahora inicia sesión.');
            window.location.href = '/login';
        } catch (error) {
            console.error(error);
        }
    });
</script>
@endsection
