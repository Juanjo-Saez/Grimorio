@extends('layouts.app')
@section('title', 'Acceso - Grimorio')

@section('content')
<div style="display: flex; align-items: center; justify-content: center; min-height: 60vh;">
    <div class="card" style="width: 100%; max-width: 450px;">
        <div class="card-body">
            <h1 style="text-align: center; margin-bottom: 2rem;">Acceso Exclusivo</h1>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" style="width: 100%;" value="{{ old('email') }}" required autofocus>
                    @error('email') <small style="color: #f87171; display: block; margin-top: 0.5rem;">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" style="width: 100%;" required>
                    @error('password') <small style="color: #f87171; display: block; margin-top: 0.5rem;">{{ $message }}</small> @enderror
                </div>

                <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                    <input type="checkbox" id="remember" name="remember" style="width: 18px; height: 18px; cursor: pointer; accent-color: var(--accent-gold);">
                    <label for="remember" style="margin-left: 0.5rem; margin-bottom: 0; cursor: pointer; font-weight: 400; color: var(--text-secondary);">Recordarme en este dispositivo</label>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; padding: 1rem;">Acceder Ahora</button>
            </form>

            <hr style="border-color: var(--glass-border); margin: 2rem 0;">
            
            <p style="text-align: center; color: var(--text-secondary);">
                ¿No tienes cuenta? 
                <a href="{{ route('register') }}" style="color: var(--accent-gold); text-decoration: none; font-weight: 600; transition: color 0.3s ease;">Regístrate aquí</a>
            </p>
        </div>
    </div>
</div>
@endsection
