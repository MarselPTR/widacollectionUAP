<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private const TOKEN_COOKIE = 'wc_token';

    public function register(Request $request, JwtService $jwt)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $email = Str::lower(trim($data['email']));

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $email,
            'password' => $data['password'],
            'is_admin' => $this->isAdminEmail($email),
        ]);

        $issued = $jwt->issue($user);
        $token = (string) ($issued['access_token'] ?? '');
        $ttlMinutes = (int) config('jwt.ttl_minutes', 120);

        return response()
            ->json($issued, 201)
            ->cookie(self::TOKEN_COOKIE, $token, $ttlMinutes, '/', null, false, false, false, 'Lax');
    }

    public function login(Request $request, JwtService $jwt)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = Str::lower(trim($data['email']));
        $user = User::query()->where('email', $email)->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        $issued = $jwt->issue($user);
        $token = (string) ($issued['access_token'] ?? '');
        $ttlMinutes = (int) config('jwt.ttl_minutes', 120);

        return response()
            ->json($issued)
            ->cookie(self::TOKEN_COOKIE, $token, $ttlMinutes, '/', null, false, false, false, 'Lax');
    }

    public function me(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => (bool) ($user->is_admin ?? false),
            ] : null,
        ]);
    }

    public function logout(Request $request, JwtService $jwt)
    {
        $token = (string) $request->cookie(self::TOKEN_COOKIE, '');
        $header = (string) $request->header('Authorization', '');
        if ($token === '' && preg_match('/^Bearer\s+(?<token>.+)$/i', $header, $m)) {
            $token = trim((string) ($m['token'] ?? ''));
        }

        if (trim($token) !== '') {
            try {
                $jwt->revoke($token);
            } catch (\Throwable $e) {
                // ignore
            }
        }

        return response()
            ->json(['message' => 'Logged out.'])
            ->withoutCookie(self::TOKEN_COOKIE);
    }

    public function updateCredentials(Request $request, JwtService $jwt)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'old_password' => ['sometimes', 'nullable', 'string'],
            'new_password' => ['sometimes', 'nullable', 'string', 'min:6'],
        ]);

        $email = array_key_exists('email', $data) && $data['email'] !== null
            ? Str::lower(trim((string) $data['email']))
            : null;

        if ($email !== null) {
            $exists = User::query()
                ->where('email', $email)
                ->where('id', '!=', $user->id)
                ->exists();
            if ($exists) {
                return response()->json(['message' => 'Email sudah digunakan.'], 422);
            }
        }

        $changingPassword = array_key_exists('new_password', $data) && $data['new_password'] !== null && trim((string) $data['new_password']) !== '';
        if ($changingPassword) {
            $old = (string) ($data['old_password'] ?? '');
            if ($old === '' || !Hash::check($old, (string) $user->password)) {
                return response()->json(['message' => 'Password lama tidak cocok.'], 422);
            }
        }

        if (array_key_exists('name', $data) && $data['name'] !== null) {
            $user->name = (string) $data['name'];
        }
        if ($email !== null) {
            $user->email = $email;
            $user->is_admin = $this->isAdminEmail($email);
        }
        if ($changingPassword) {
            $user->password = (string) $data['new_password'];
        }
        $user->save();

        // Re-issue token to reflect email/is_admin changes.
        $currentToken = (string) $request->cookie(self::TOKEN_COOKIE, '');
        $header = (string) $request->header('Authorization', '');
        if ($currentToken === '' && preg_match('/^Bearer\s+(?<token>.+)$/i', $header, $m)) {
            $currentToken = trim((string) ($m['token'] ?? ''));
        }
        if ($currentToken !== '') {
            try {
                $jwt->revoke($currentToken);
            } catch (\Throwable $e) {
                // ignore
            }
        }

        $issued = $jwt->issue($user);
        $token = (string) ($issued['access_token'] ?? '');
        $ttlMinutes = (int) config('jwt.ttl_minutes', 120);

        return response()
            ->json($issued)
            ->cookie(self::TOKEN_COOKIE, $token, $ttlMinutes, '/', null, false, true, false, 'Lax');
    }

    private function isAdminEmail(string $email): bool
    {
        $raw = (string) env('ADMIN_EMAILS', '');
        if ($raw === '')
            return false;
        $list = array_filter(array_map('trim', explode(',', strtolower($raw))));
        return in_array(strtolower($email), $list, true);
    }
}
