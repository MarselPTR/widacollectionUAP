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

        return response()->json($jwt->issue($user), 201);
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

        return response()->json($jwt->issue($user));
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
        $header = (string) $request->header('Authorization', '');
        if (preg_match('/^Bearer\s+(?<token>.+)$/i', $header, $m)) {
            $token = trim((string) ($m['token'] ?? ''));
            if ($token !== '') {
                try {
                    $jwt->revoke($token);
                } catch (\Throwable $e) {
                    // ignore
                }
            }
        }

        return response()->json(['message' => 'Logged out.']);
    }

    private function isAdminEmail(string $email): bool
    {
        $raw = (string) env('ADMIN_EMAILS', '');
        if ($raw === '') return false;
        $list = array_filter(array_map('trim', explode(',', strtolower($raw))));
        return in_array(strtolower($email), $list, true);
    }
}
