<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    // Public: list reviews for a product public_id
    public function index(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'string', 'max:255'],
        ]);

        $rows = DB::table('reviews')
            ->where('product_id', $data['product_id'])
            ->orderByDesc('reviewed_at')
            ->orderByDesc('id')
            ->get();

        return response()->json(['data' => $rows]);
    }

    // Authenticated: create/update review (optionally by order_uuid)
    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'product_id' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string'],
            'author' => ['nullable', 'string', 'max:255'],
            'order_uuid' => ['nullable', 'string', 'max:36'],
        ]);

        $orderId = null;
        if (!empty($data['order_uuid'])) {
            $order = DB::table('orders')->where('uuid', $data['order_uuid'])->where('user_id', $user->id)->first();
            if (!$order) {
                return response()->json(['message' => 'Order not found.'], 404);
            }
            $orderId = (int) $order->id;

            $existingByOrder = DB::table('reviews')->where('order_id', $orderId)->first();
            if ($existingByOrder) {
                DB::table('reviews')->where('id', $existingByOrder->id)->update([
                    'rating' => (int) $data['rating'],
                    'comment' => $data['comment'] ?? null,
                    'author' => $data['author'] ?? $user->name,
                    'email' => $user->email,
                    'updated_at' => now(),
                ]);
                return response()->json(['data' => DB::table('reviews')->where('id', $existingByOrder->id)->first()]);
            }
        }

        $uuid = (string) Str::uuid();
        $publicId = 'rev-' . now()->format('YmdHis') . '-' . Str::lower(Str::random(6));

        DB::table('reviews')->insert([
            'uuid' => $uuid,
            'public_id' => $publicId,
            'user_id' => $user->id,
            'order_id' => $orderId,
            'product_id' => $data['product_id'],
            'rating' => (int) $data['rating'],
            'comment' => $data['comment'] ?? null,
            'author' => $data['author'] ?? $user->name,
            'email' => $user->email,
            'reviewed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['data' => DB::table('reviews')->where('uuid', $uuid)->first()], 201);
    }

    public function mine(Request $request)
    {
        $user = $request->user();

        $rows = DB::table('reviews')
            ->leftJoin('orders', 'reviews.order_id', '=', 'orders.id')
            ->where('reviews.user_id', $user->id)
            ->select([
                'reviews.*',
                'orders.uuid as order_uuid',
            ])
            ->orderByDesc('reviews.reviewed_at')
            ->orderByDesc('reviews.id')
            ->get();

        return response()->json(['data' => $rows]);
    }

    public function destroy(Request $request, string $uuid)
    {
        $user = $request->user();
        DB::table('reviews')->where('user_id', $user->id)->where('uuid', $uuid)->delete();
        return response()->json(['message' => 'Deleted.']);
    }
}
