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
        $header = (string) $request->header('Authorization', '');
        if (!preg_match('/^Bearer\s+(?<token>.+)$/i', $header, $m)) {
            return response()->json(['message' => 'Missing Bearer token.'], 401);
        }

        $token = trim((string) ($m['token'] ?? ''));
        if ($token === '') {
            return response()->json(['message' => 'Missing Bearer token.'], 401);
        }

        try {
            /** @var JwtService $jwt */
            $jwt = app(JwtService::class);
            $payload = $jwt->decode($token);

            if ($jwt->isRevoked($payload)) {
                return response()->json(['message' => 'Token revoked.'], 401);
            }

            $userId = Arr::get($payload, 'sub');
            if (!$userId) {
                return response()->json(['message' => 'Invalid token.'], 401);
            }

            $user = User::query()->find($userId);
            if (!$user) {
                return response()->json(['message' => 'User not found.'], 401);
            }

            $request->setUserResolver(fn () => $user);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Invalid token.'], 401);
        }

        return $next($request);
    }
}
