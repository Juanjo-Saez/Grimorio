<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function signup(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Crear el usuario
        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Preguntar sobre users.index
        return redirect()->route('home')->with('success', 'Usuario creado exitosamente.');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($user && Hash::check($validated['password'], $user->password)){
            Auth::login($user);
            return redirect()->route('home');
        } else {
            return back()->withErrors([
                'email' => 'Las credenciales no coinciden.',
            ])->onlyInput('email');
        }
    }
}
