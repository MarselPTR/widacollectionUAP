<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $orders = DB::table('orders')
            ->where('user_id', $user->id)
            ->orderByDesc('placed_at')
            ->orderByDesc('id')
            ->get();

        return response()->json(['data' => $orders]);
    }

    public function markReceived(Request $request, string $uuid)
    {
        $user = $request->user();

        $order = DB::table('orders')->where('uuid', $uuid)->where('user_id', $user->id)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }
        if ($order->status !== 'shipped') {
            return response()->json(['message' => 'Invalid status transition.'], 422);
        }

        DB::table('orders')->where('id', $order->id)->update([
            'status' => 'delivered',
            'delivered_at' => now(),
            'status_note' => 'Selesai - Diterima',
            'updated_at' => now(),
        ]);

        return response()->json(['data' => DB::table('orders')->where('id', $order->id)->first()]);
    }

    public function show(Request $request, string $uuid)
    {
        $user = $request->user();

        $order = DB::table('orders')->where('uuid', $uuid)->where('user_id', $user->id)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        $items = DB::table('order_items')->where('order_id', $order->id)->orderBy('id')->get();

        return response()->json([
            'data' => [
                'order' => $order,
                'items' => $items,
            ],
        ]);
    }

    public function checkout(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'customer' => ['required', 'array'],
            'customer.fullname' => ['required', 'string', 'max:255'],
            'customer.phone' => ['required', 'string', 'max:50'],
            'customer.email' => ['nullable', 'email', 'max:255'],
            'customer.address' => ['required', 'string'],
            'customer.notes' => ['nullable', 'string'],
            'payment' => ['nullable', 'string', 'max:50'],
            'shipping_value' => ['nullable', 'integer', 'min:0'],
            'shipping_label' => ['nullable', 'string', 'max:255'],
        ]);

        $result = DB::transaction(function () use ($user, $data) {
            $cart = DB::table('carts')->where('user_id', $user->id)->orderByDesc('id')->first();
            if (!$cart) {
                return ['error' => response()->json(['message' => 'Cart is empty.'], 422)];
            }

            $items = DB::table('cart_items')->where('cart_id', $cart->id)->orderBy('id')->get();
            if ($items->isEmpty()) {
                return ['error' => response()->json(['message' => 'Cart is empty.'], 422)];
            }

            // Resolve shipping
            $shippingValue = array_key_exists('shipping_value', $data)
                ? (int) ($data['shipping_value'] ?? 0)
                : (int) ($cart->shipping_value ?? 0);
            $shippingLabel = array_key_exists('shipping_label', $data)
                ? ($data['shipping_label'] ?? null)
                : ($cart->shipping_label ?? null);

            $subtotal = 0;
            foreach ($items as $it) {
                $subtotal += ((int) $it->price) * ((int) $it->quantity);
            }
            $total = $subtotal + $shippingValue;

            $removedItems = [];

            // Stock check + decrement
            foreach ($items as $it) {
                if (!$it->product_id)
                    continue;

                $product = DB::table('custom_products')->where('public_id', $it->product_id)->first();

                // Self-healing: if ID mismatch (e.g. after re-seed), try finding by name
                if (!$product && $it->name) {
                    $product = DB::table('custom_products')->where('title', $it->name)->first();
                    if ($product) {
                        // Check if we already have this valid product in the cart (to avoid Unique Constraint violation)
                        $existing = DB::table('cart_items')
                            ->where('cart_id', $cart->id)
                            ->where('product_id', $product->public_id)
                            ->first();

                        if ($existing) {
                            // Merge: Add qty to existing, delete stale current
                            DB::table('cart_items')->where('id', $existing->id)->increment('quantity', (int) $it->quantity);
                            DB::table('cart_items')->where('id', $it->id)->delete();

                            // We processed this item by merging it. We still need to decrement stock for THIS item's quantity 
                            // to keep the order logic consistent (since we are creating an order for it).
                            // Proceed as if it's a valid item for the duration of this transaction.
                        } else {
                            // No duplicate, safe to update
                            DB::table('cart_items')->where('id', $it->id)->update([
                                'product_id' => $product->public_id,
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }

                if (!$product) {
                    // Auto-remove dead items
                    DB::table('cart_items')->where('id', $it->id)->delete();
                    $removedItems[] = $it->name;
                    continue;
                }

                if ((int) $product->stock < (int) $it->quantity) {
                    return [
                        'error' => response()->json([
                            'message' => 'Stok tidak mencukupi untuk ' . $product->title . '. Tersisa: ' . $product->stock,
                        ], 422)
                    ];
                }

                DB::table('custom_products')
                    ->where('id', $product->id)
                    ->decrement('stock', (int) $it->quantity);
            }

            if (!empty($removedItems)) {
                return [
                    'error' => response()->json([
                        'message' => 'Beberapa produk tidak lagi tersedia dan telah dihapus dari keranjang: ' . implode(', ', $removedItems) . '. Silakan review dan checkout ulang.',
                    ], 422)
                ];
            }

            $orderUuid = (string) Str::uuid();
            $orderPublic = $this->generatePublicId();
            $placedAt = now();

            $first = $items->first();
            DB::table('orders')->insert([
                'uuid' => $orderUuid,
                'user_id' => $user->id,
                'public_id' => $orderPublic,
                'product_id' => $first?->product_id,
                'product_title' => $first?->name,
                'product_image' => $first?->image,
                'status' => 'packed',
                'status_note' => 'Sedang dikemas',
                'shipping_label' => $shippingLabel,
                'currency' => 'IDR',
                'total' => $total,
                'placed_at' => $placedAt,
                'delivered_at' => null,
                'customer_snapshot' => json_encode([
                    'customer' => $data['customer'],
                    'payment' => $data['payment'] ?? null,
                    'shipping_value' => $shippingValue,
                    'shipping_label' => $shippingLabel,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $order = DB::table('orders')->where('uuid', $orderUuid)->first();

            foreach ($items as $it) {
                DB::table('order_items')->insert([
                    'uuid' => (string) Str::uuid(),
                    'order_id' => $order->id,
                    'product_id' => $it->product_id,
                    'name' => $it->name,
                    'category' => $it->category,
                    'image' => $it->image,
                    'price' => (int) $it->price,
                    'quantity' => (int) $it->quantity,
                    'note' => $data['customer']['notes'] ?? null,
                    'raw' => $it->raw,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Clear cart
            DB::table('cart_items')->where('cart_id', $cart->id)->delete();
            DB::table('carts')->where('id', $cart->id)->update([
                'shipping_value' => $shippingValue,
                'shipping_label' => $shippingLabel,
                'updated_at' => now(),
            ]);

            return [
                'ok' => response()->json([
                    'data' => [
                        'order' => $order,
                    ],
                ], 201)
            ];
        });

        if (isset($result['error'])) {
            return $result['error'];
        }

        return $result['ok'];
    }

    // Admin
    public function adminIndex()
    {
        $orders = DB::table('orders')
            ->select([
                'orders.*',
                DB::raw('(SELECT COALESCE(SUM(order_items.quantity), 0) FROM order_items WHERE order_items.order_id = orders.id) as total_qty'),
            ])
            ->orderByDesc('placed_at')
            ->orderByDesc('id')
            ->get();
        return response()->json(['data' => $orders]);
    }

    public function adminMarkShipped(string $uuid, Request $request)
    {
        $data = $request->validate([
            'shipping_label' => ['nullable', 'string', 'max:255'],
            'status_note' => ['nullable', 'string', 'max:255'],
        ]);

        $order = DB::table('orders')->where('uuid', $uuid)->first();
        if (!$order)
            return response()->json(['message' => 'Order not found.'], 404);
        if ($order->status !== 'packed')
            return response()->json(['message' => 'Invalid status transition.'], 422);

        DB::table('orders')->where('id', $order->id)->update([
            'status' => 'shipped',
            'shipping_label' => $data['shipping_label'] ?? $order->shipping_label,
            'status_note' => $data['status_note'] ?? (($data['shipping_label'] ?? $order->shipping_label) ? (($data['shipping_label'] ?? $order->shipping_label) . ' - Dalam pengiriman') : 'Dalam pengiriman'),
            'updated_at' => now(),
        ]);

        return response()->json(['data' => DB::table('orders')->where('id', $order->id)->first()]);
    }

    public function adminMarkDelivered(string $uuid)
    {
        $order = DB::table('orders')->where('uuid', $uuid)->first();
        if (!$order)
            return response()->json(['message' => 'Order not found.'], 404);
        if ($order->status !== 'shipped')
            return response()->json(['message' => 'Invalid status transition.'], 422);

        DB::table('orders')->where('id', $order->id)->update([
            'status' => 'delivered',
            'delivered_at' => now(),
            'status_note' => 'Selesai - Diterima',
            'updated_at' => now(),
        ]);

        return response()->json(['data' => DB::table('orders')->where('id', $order->id)->first()]);
    }

    private function generatePublicId(): string
    {
        // Similar to old WCxxxxxx vibe, but ensure uniqueness.
        return 'WC' . now()->format('Ymd') . Str::upper(Str::random(4));
    }
}
