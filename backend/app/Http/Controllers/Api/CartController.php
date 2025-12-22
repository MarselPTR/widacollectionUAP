<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $cart = $this->getOrCreateCart((int) $user->id);

        $items = DB::table('cart_items')
            ->select([
                'uuid',
                'product_id',
                'name',
                'category',
                'image',
                'price',
                'quantity',
                'description',
                'raw',
                'created_at',
                'updated_at',
            ])
            ->where('cart_id', $cart->id)
            ->orderByDesc('created_at')
            ->get();

        $subtotal = 0;
        foreach ($items as $it) {
            $subtotal += ((int) $it->price) * ((int) $it->quantity);
        }
        $shipping = (int) ($cart->shipping_value ?? 0);
        $total = $subtotal + $shipping;

        return response()->json([
            'data' => [
                'cart' => [
                    'uuid' => $cart->uuid,
                    'public_id' => $cart->public_id,
                    'currency' => $cart->currency,
                    'shipping_value' => $cart->shipping_value,
                    'shipping_label' => $cart->shipping_label,
                ],
                'items' => $items,
                'summary' => [
                    'subtotal' => $subtotal,
                    'shipping' => $shipping,
                    'total' => $total,
                ],
            ],
        ]);
    }

    public function setShipping(Request $request)
    {
        $user = $request->user();
        $cart = $this->getOrCreateCart((int) $user->id);

        $data = $request->validate([
            'shipping_value' => ['required', 'integer', 'min:0'],
            'shipping_label' => ['nullable', 'string', 'max:255'],
        ]);

        DB::table('carts')->where('id', $cart->id)->update([
            'shipping_value' => (int) $data['shipping_value'],
            'shipping_label' => $data['shipping_label'] ?? null,
            'updated_at' => now(),
        ]);

        return $this->show($request);
    }

    public function addItem(Request $request)
    {
        $user = $request->user();
        $cart = $this->getOrCreateCart((int) $user->id);

        $data = $request->validate([
            // Accept either product public id (cust-...) or slug
            'product_id' => ['nullable', 'string', 'max:255'],
            'product_slug' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = null;
        if (!empty($data['product_id'])) {
            $product = DB::table('custom_products')->where('public_id', $data['product_id'])->first();
        } elseif (!empty($data['product_slug'])) {
            $product = DB::table('custom_products')->where('slug', $data['product_slug'])->first();
        }

        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $productId = (string) $product->public_id;
        $existing = DB::table('cart_items')
            ->where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            DB::table('cart_items')->where('id', $existing->id)->update([
                'quantity' => (int) $existing->quantity + (int) $data['quantity'],
                'updated_at' => now(),
            ]);
        } else {
            DB::table('cart_items')->insert([
                'uuid' => (string) Str::uuid(),
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'name' => (string) ($product->title ?? 'Produk'),
                'category' => $product->category ?? null,
                'image' => $product->image ?? null,
                'price' => (int) ($product->price ?? 0),
                'quantity' => (int) $data['quantity'],
                'description' => $product->description ?? null,
                'raw' => json_encode([
                    'product_uuid' => $product->uuid ?? null,
                    'slug' => $product->slug ?? null,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $this->show($request);
    }

    public function updateItem(Request $request, string $uuid)
    {
        $user = $request->user();
        $cart = $this->getOrCreateCart((int) $user->id);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $item = DB::table('cart_items')->where('cart_id', $cart->id)->where('uuid', $uuid)->first();
        if (!$item) {
            return response()->json(['message' => 'Cart item not found.'], 404);
        }

        DB::table('cart_items')->where('id', $item->id)->update([
            'quantity' => (int) $data['quantity'],
            'updated_at' => now(),
        ]);

        return $this->show($request);
    }

    public function removeItem(Request $request, string $uuid)
    {
        $user = $request->user();
        $cart = $this->getOrCreateCart((int) $user->id);

        DB::table('cart_items')->where('cart_id', $cart->id)->where('uuid', $uuid)->delete();
        return $this->show($request);
    }

    public function clear(Request $request)
    {
        $user = $request->user();
        $cart = $this->getOrCreateCart((int) $user->id);

        DB::table('cart_items')->where('cart_id', $cart->id)->delete();
        return $this->show($request);
    }

    private function getOrCreateCart(int $userId)
    {
        $cart = DB::table('carts')->where('user_id', $userId)->orderByDesc('id')->first();
        if ($cart) return $cart;

        $uuid = (string) Str::uuid();
        $publicId = 'cart-' . now()->format('YmdHis') . '-' . Str::lower(Str::random(6));

        DB::table('carts')->insert([
            'uuid' => $uuid,
            'public_id' => $publicId,
            'user_id' => $userId,
            'session_id' => null,
            'currency' => 'IDR',
            'shipping_value' => 0,
            'shipping_label' => 'Reguler 3-4 hari',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('carts')->where('uuid', $uuid)->first();
    }
}
