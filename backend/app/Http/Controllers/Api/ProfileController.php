<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        $profile = DB::table('profiles')->where('user_id', $user->id)->first();
        if (!$profile) {
            $uuid = (string) Str::uuid();
            DB::table('profiles')->insert([
                'uuid' => $uuid,
                'user_id' => $user->id,
                'display_name' => $user->name,
                'phone' => null,
                'bio' => null,
                'city' => null,
                'avatar_data_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $profile = DB::table('profiles')->where('uuid', $uuid)->first();
        }

        return response()->json(['data' => $profile]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'display_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'bio' => ['sometimes', 'nullable', 'string'],
            'city' => ['sometimes', 'nullable', 'string', 'max:255'],
            'avatar_data_url' => ['sometimes', 'nullable', 'string'],
        ]);

        $profile = DB::table('profiles')->where('user_id', $user->id)->first();
        if (!$profile) {
            $this->show($request);
            $profile = DB::table('profiles')->where('user_id', $user->id)->first();
        }

        DB::table('profiles')->where('id', $profile->id)->update(array_merge($data, [
            'updated_at' => now(),
        ]));

        return $this->show($request);
    }
}
