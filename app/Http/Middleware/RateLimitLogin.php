<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitLogin
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $key = "login_attempts:{$ip}";
        $maxAttempts = 5;
        $decayMinutes = 1;

        if ($this->limiter->tooManyAttempts($key, $maxAttempts, $decayMinutes)) {
            $seconds = $this->limiter->availableIn($key);
            return response()->json([
                'error' => 'Demasiados intentos de login. Intenta en ' . $seconds . ' segundos.',
            ], 429);
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        return $next($request);
    }
}
