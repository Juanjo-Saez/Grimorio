<?php

namespace App\Features\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Features\Auth\Services\AuthService;
use App\Features\Auth\Services\JwtService;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;
    protected $jwtService;

    public function __construct(AuthService $authService, JwtService $jwtService)
    {
        $this->authService = $authService;
        $this->jwtService = $jwtService;
    }

    /**
     * Registrar usuario
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $this->authService->register($validated['email'], $validated['password']);

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'user' => $user,
        ], 201);
    }

    /**
     * Iniciar sesión
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = $this->authService->login($validated['email'], $validated['password']);

        if (!$user) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        $tokenData = $this->jwtService->generateToken($user);

        return response()->json([
            'token' => $tokenData['token'],
            'expires_in' => $tokenData['expires_in'],
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        // En MVP, logout es simplemente eliminar token en client
        return response()->json(null, 204);
    }
}
