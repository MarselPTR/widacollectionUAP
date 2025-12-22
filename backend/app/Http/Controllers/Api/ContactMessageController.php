<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContactMessageController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'source' => ['nullable', 'string', 'max:255'],
        ]);

        $uuid = (string) Str::uuid();
        $publicId = 'msg-' . now()->format('YmdHis') . '-' . Str::lower(Str::random(6));

        $user = $request->user();

        DB::table('contact_messages')->insert([
            'uuid' => $uuid,
            'public_id' => $publicId,
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
            'user_email' => $user?->email,
            'source' => $data['source'] ?? null,
            'submitted_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['data' => DB::table('contact_messages')->where('uuid', $uuid)->first()], 201);
    }

    public function adminIndex()
    {
        $rows = DB::table('contact_messages')->orderByDesc('submitted_at')->orderByDesc('id')->get();
        return response()->json(['data' => $rows]);
    }

    public function destroy(string $uuid)
    {
        $deleted = DB::table('contact_messages')->where('uuid', $uuid)->delete();
        if (!$deleted) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->noContent();
    }
}
