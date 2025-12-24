<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Log;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        Log::info('AdminOnly: Check User', ['user_id' => $user?->id ?? 'null', 'is_admin' => $user?->is_admin ?? 'null']);

        if (!$user) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Unauthorized.'], 401);
            }
            return redirect('/login');
        }

        if (($user->is_admin ?? false) !== true) {
            Log::warning('AdminOnly: Access Denied', ['user_id' => $user->id]);
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }
            abort(403);
        }

        return $next($request);
    }
}
