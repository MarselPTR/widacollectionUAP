<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class JwtAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $this->extractToken($request);
        if ($token === null || trim($token) === '') {
            return $this->unauthorized($request, 'Missing token.');
        }

        try {
            /** @var JwtService $jwt */
            $jwt = app(JwtService::class);
            $payload = $jwt->decode($token);

            if ($jwt->isRevoked($payload)) {
                return $this->unauthorized($request, 'Token revoked.');
            }

            $userId = Arr::get($payload, 'sub');
            if (!$userId) {
                return $this->unauthorized($request, 'Invalid token.');
            }

            $user = User::query()->find($userId);
            if (!$user) {
                return $this->unauthorized($request, 'User not found.');
            }

            $request->setUserResolver(fn () => $user);
        } catch (\Throwable $e) {
            return $this->unauthorized($request, 'Invalid token.');
        }

        return $next($request);
    }

    private function extractToken(Request $request): ?string
    {
        $header = (string) $request->header('Authorization', '');
        if (preg_match('/^Bearer\s+(?<token>.+)$/i', $header, $m)) {
            $token = trim((string) ($m['token'] ?? ''));
            if ($token !== '') return $token;
        }

        // Support cookie-based auth for normal web navigation.
        $cookie = (string) $request->cookie('wc_token', '');
        $cookie = trim($cookie);
        if ($cookie !== '') return $cookie;

        return null;
    }

    private function unauthorized(Request $request, string $message): Response
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['message' => $message], 401);
        }

        return redirect('/login.html');
    }
}
