<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $rows = DB::table('wishlist_items')
            ->where('user_id', $user->id)
            ->orderByDesc('added_at')
            ->orderByDesc('id')
            ->get();

        return response()->json(['data' => $rows]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'product_id' => ['nullable', 'string', 'max:255'],
            'product_slug' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
        ]);

        $product = null;
        if (!empty($data['product_id'])) {
            $product = DB::table('custom_products')->where('public_id', $data['product_id'])->first();
        } elseif (!empty($data['product_slug'])) {
            $product = DB::table('custom_products')->where('slug', $data['product_slug'])->first();
        }

        if (!$product && empty($data['title'])) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $productId = $product ? (string) $product->public_id : null;

        // Prevent duplicates per (user_id, product_id)
        if ($productId) {
            $exists = DB::table('wishlist_items')->where('user_id', $user->id)->where('product_id', $productId)->first();
            if ($exists) {
                return response()->json(['data' => $exists]);
            }
        }

        $uuid = (string) Str::uuid();
        DB::table('wishlist_items')->insert([
            'uuid' => $uuid,
            'user_id' => $user->id,
            'product_id' => $productId,
            'title' => $product ? (string) ($product->title ?? 'Wishlist') : (string) $data['title'],
            'image' => $product?->image,
            'price' => (int) ($product?->price ?? 0),
            'category' => $product?->category,
            'badge' => null,
            'added_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['data' => DB::table('wishlist_items')->where('uuid', $uuid)->first()], 201);
    }

    public function destroy(Request $request, string $uuid)
    {
        $user = $request->user();
        DB::table('wishlist_items')->where('user_id', $user->id)->where('uuid', $uuid)->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    public function clear(Request $request)
    {
        $user = $request->user();
        DB::table('wishlist_items')->where('user_id', $user->id)->delete();
        return response()->json(['message' => 'Cleared.']);
    }
}
