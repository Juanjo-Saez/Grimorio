<?php

namespace App\Features\Auth\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Registrar un usuario
     */
    public function register(string $email, string $password): User
    {
        return User::create([
            'email' => $email,
            'password_hash' => Hash::make($password),
        ]);
    }

    /**
     * Autenticar y devolver usuario
     */
    public function login(string $email, string $password): ?User
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password_hash)) {
            return null;
        }

        return $user;
    }

    /**
     * Validar contraseña
     */
    public function validatePassword(string $password, string $hash): bool
    {
        return Hash::check($password, $hash);
    }
}
