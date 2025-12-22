<?php

namespace App\Services;

use App\Models\JwtBlacklist;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class JwtService
{
    public function issue(User $user, array $claims = []): array
    {
        $now = time();
        $ttlMinutes = (int) config('jwt.ttl_minutes', 120);
        $exp = $now + ($ttlMinutes * 60);

        $payload = array_merge([
            'iss' => (string) config('jwt.issuer', ''),
            'sub' => (string) $user->getAuthIdentifier(),
            'iat' => $now,
            'nbf' => $now,
            'exp' => $exp,
            'jti' => (string) Str::uuid(),
            'email' => (string) $user->email,
            'is_admin' => (bool) ($user->is_admin ?? false),
        ], $claims);

        $token = JWT::encode($payload, $this->secret(), 'HS256');

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $exp - $now,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => (bool) ($user->is_admin ?? false),
            ],
        ];
    }

    public function decode(string $token): array
    {
        JWT::$leeway = (int) config('jwt.leeway_seconds', 30);
        $decoded = JWT::decode($token, new Key($this->secret(), 'HS256'));
        return json_decode(json_encode($decoded), true);
    }

    public function revoke(string $token): void
    {
        $payload = $this->decode($token);
        $jti = Arr::get($payload, 'jti');
        $exp = (int) Arr::get($payload, 'exp', 0);
        if (!$jti || $exp <= 0) {
            return;
        }

        JwtBlacklist::query()->updateOrCreate(
            ['jti' => (string) $jti],
            ['expires_at' => date('Y-m-d H:i:s', $exp)]
        );
    }

    public function isRevoked(array $payload): bool
    {
        $jti = Arr::get($payload, 'jti');
        if (!$jti) return false;
        return JwtBlacklist::query()->where('jti', (string) $jti)->exists();
    }

    private function secret(): string
    {
        $explicit = (string) (config('jwt.secret') ?? '');
        if ($explicit !== '') {
            return $explicit;
        }

        $appKey = (string) config('app.key');
        if (str_starts_with($appKey, 'base64:')) {
            $decoded = base64_decode(substr($appKey, 7), true);
            if ($decoded !== false) {
                return $decoded;
            }
        }

        return $appKey;
    }
}
