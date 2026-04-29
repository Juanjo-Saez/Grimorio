<?php

namespace App\Features\Auth\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;

class JwtService
{
    protected $secret;
    protected $expiration;
    protected $algorithm = 'HS256';

    public function __construct()
    {
        $this->secret = env('JWT_SECRET', 'secret-key');
        $this->expiration = (int) env('JWT_EXPIRATION', 3600);
    }

    /**
     * Generar JWT
     */
    public function generateToken(User $user): array
    {
        $now = time();
        $expires = $now + $this->expiration;

        $payload = [
            'sub' => $user->id,
            'email' => $user->email,
            'iat' => $now,
            'exp' => $expires,
            'iss' => 'grimorio',
        ];

        $token = JWT::encode($payload, $this->secret, $this->algorithm);

        return [
            'token' => $token,
            'expires_in' => $this->expiration,
        ];
    }

    /**
     * Validar y decodificar JWT
     */
    public function validateToken(string $token): ?object
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, $this->algorithm));
            return $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Extraer token del header
     */
    public static function extractToken(string $authHeader): ?string
    {
        if (preg_match('/Bearer\s+(.+)/i', $authHeader, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
