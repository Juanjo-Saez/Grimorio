@extends('layouts.app')
@section('title', 'Registro - Grimorio')

@section('content')
<div style="display: flex; align-items: center; justify-content: center; min-height: 60vh;">
    <div class="card" style="width: 100%; max-width: 450px;">
        <div class="card-body">
            <h1 style="text-align: center; margin-bottom: 2rem;">Crear Cuenta</h1>
            <p style="text-align: center; color: var(--text-secondary); margin-bottom: 2rem;">Únete a Grimorio y comienza a organizar tus pensamientos</p>
            
            @if (session('info'))
                <div style="background: rgba(212, 175, 55, 0.1); border-left: 4px solid var(--accent-gold); padding: 1rem; margin-bottom: 1.5rem; border-radius: 0.25rem;">
                    <p style="margin: 0; color: var(--accent-gold); font-size: 0.9rem;">{{ session('info') }}</p>
                </div>
            @endif
            
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" style="width: 100%;" value="{{ old('email') }}" required autofocus>
                    @error('email') <small style="color: #f87171; display: block; margin-top: 0.5rem;">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" style="width: 100%;" required>
                    <small style="color: var(--text-secondary); display: block; margin-top: 0.5rem;">Mínimo 8 caracteres</small>
                    @error('password') <small style="color: #f87171; display: block; margin-top: 0.25rem;">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" style="width: 100%;" required>
                    @error('password_confirmation') <small style="color: #f87171; display: block; margin-top: 0.5rem;">{{ $message }}</small> @enderror
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; padding: 1rem; margin-bottom: 1rem;">Crear Cuenta</button>
            </form>

            <hr style="border-color: var(--glass-border); margin: 2rem 0;">
            
            <p style="text-align: center; color: var(--text-secondary);">
                ¿Ya tienes cuenta? 
                <a href="{{ route('login') }}" style="color: var(--accent-gold); text-decoration: none; font-weight: 600; transition: color 0.3s ease;">Accede aquí</a>
            </p>
        </div>
    </div>
</div>
@endsection
