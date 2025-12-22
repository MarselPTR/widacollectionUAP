<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $rows = DB::table('addresses')
            ->where('user_id', $user->id)
            ->orderByDesc('is_primary')
            ->orderByDesc('id')
            ->get();

        return response()->json(['data' => $rows]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'recipient' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'detail' => ['required', 'string'],
            'maps_address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'postal' => ['nullable', 'string', 'max:20'],
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
            'is_primary' => ['nullable', 'boolean'],
        ]);

        $uuid = (string) Str::uuid();

        if (!empty($data['is_primary'])) {
            DB::table('addresses')->where('user_id', $user->id)->update(['is_primary' => false]);
        }

        DB::table('addresses')->insert([
            'uuid' => $uuid,
            'user_id' => $user->id,
            'label' => $data['label'],
            'recipient' => $data['recipient'],
            'phone' => $data['phone'],
            'detail' => $data['detail'],
            'maps_address' => $data['maps_address'] ?? null,
            'city' => $data['city'] ?? null,
            'postal' => $data['postal'] ?? null,
            'lat' => $data['lat'] ?? null,
            'lng' => $data['lng'] ?? null,
            'is_primary' => (bool) ($data['is_primary'] ?? false),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['data' => DB::table('addresses')->where('uuid', $uuid)->first()], 201);
    }

    public function update(Request $request, string $uuid)
    {
        $user = $request->user();
        $addr = DB::table('addresses')->where('user_id', $user->id)->where('uuid', $uuid)->first();
        if (!$addr) return response()->json(['message' => 'Address not found.'], 404);

        $data = $request->validate([
            'label' => ['sometimes', 'required', 'string', 'max:255'],
            'recipient' => ['sometimes', 'required', 'string', 'max:255'],
            'phone' => ['sometimes', 'required', 'string', 'max:50'],
            'detail' => ['sometimes', 'required', 'string'],
            'maps_address' => ['sometimes', 'nullable', 'string'],
            'city' => ['sometimes', 'nullable', 'string', 'max:255'],
            'postal' => ['sometimes', 'nullable', 'string', 'max:20'],
            'lat' => ['sometimes', 'nullable', 'numeric'],
            'lng' => ['sometimes', 'nullable', 'numeric'],
            'is_primary' => ['sometimes', 'boolean'],
        ]);

        if (array_key_exists('is_primary', $data) && $data['is_primary']) {
            DB::table('addresses')->where('user_id', $user->id)->update(['is_primary' => false]);
        }

        DB::table('addresses')->where('id', $addr->id)->update(array_merge($data, ['updated_at' => now()]));
        return response()->json(['data' => DB::table('addresses')->where('id', $addr->id)->first()]);
    }

    public function destroy(Request $request, string $uuid)
    {
        $user = $request->user();
        DB::table('addresses')->where('user_id', $user->id)->where('uuid', $uuid)->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    public function setPrimary(Request $request, string $uuid)
    {
        $user = $request->user();
        $addr = DB::table('addresses')->where('user_id', $user->id)->where('uuid', $uuid)->first();
        if (!$addr) return response()->json(['message' => 'Address not found.'], 404);

        DB::table('addresses')->where('user_id', $user->id)->update(['is_primary' => false]);
        DB::table('addresses')->where('id', $addr->id)->update(['is_primary' => true, 'updated_at' => now()]);

        return response()->json(['data' => DB::table('addresses')->where('id', $addr->id)->first()]);
    }
}
