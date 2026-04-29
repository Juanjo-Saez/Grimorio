<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Features\Auth\Services\JwtService;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    protected $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader) {
            return response()->json(['error' => 'Token no proporcionado'], 401);
        }

        $token = JwtService::extractToken($authHeader);

        if (!$token) {
            return response()->json(['error' => 'Formato de token inválido'], 401);
        }

        $decoded = $this->jwtService->validateToken($token);

        if (!$decoded) {
            return response()->json(['error' => 'Token inválido o expirado'], 401);
        }

        $user = User::find($decoded->sub);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 401);
        }

        // Inyectar usuario autenticado
        auth()->setUser($user);
        $request->setUserResolver(fn () => $user);
        $request->userId = $user->id;

        return $next($request);
    }
}
