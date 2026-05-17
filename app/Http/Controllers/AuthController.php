<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Si hay share_token en URL o sesión, redirija allí
            $shareToken = $request->input('share_token') ?? session('share_token');
            if ($shareToken) {
                $request->session()->forget('share_token');
                return redirect()->route('shared.show', ['token' => $shareToken]);
            }
            
            return redirect()->intended(route('notes.index'));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Credenciales incorrectas.']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = \App\Models\User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        // Si hay share_token en URL o sesión, redirija allí
        $shareToken = $request->input('share_token') ?? session('share_token');
        if ($shareToken) {
            $request->session()->forget('share_token');
            return redirect()->route('shared.show', ['token' => $shareToken])->with('success', '¡Bienvenido a Grimorio!');
        }

        return redirect(route('notes.index'))->with('success', '¡Bienvenido a Grimorio!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
