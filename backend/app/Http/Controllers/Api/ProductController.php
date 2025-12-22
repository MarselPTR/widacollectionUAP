<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $rows = DB::table('custom_products')
            ->select([
                'uuid',
                'public_id',
                'slug',
                'title',
                'description',
                'category',
                'type',
                'image',
                'price',
                'stock',
                'created_at',
                'updated_at',
            ])
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['data' => $rows]);
    }

    public function show(string $slug)
    {
        $row = DB::table('custom_products')
            ->select([
                'uuid',
                'public_id',
                'slug',
                'title',
                'description',
                'category',
                'type',
                'image',
                'price',
                'stock',
                'created_at',
                'updated_at',
            ])
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug)
                    ->orWhere('public_id', $slug);
            })
            ->first();

        if (!$row) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        return response()->json(['data' => $row]);
    }

    // Admin CRUD
    public function adminIndex()
    {
        return $this->index();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'slug' => ['nullable', 'string', 'max:255'],
        ]);

        $uuid = (string) Str::uuid();
        $publicId = $this->generatePublicId();
        $slug = $this->ensureUniqueSlug($data['slug'] ?? null, $data['title']);

        DB::table('custom_products')->insert([
            'uuid' => $uuid,
            'public_id' => $publicId,
            'slug' => $slug,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'category' => $data['category'] ?? null,
            'type' => $data['type'] ?? null,
            'image' => $data['image'] ?? null,
            'price' => (int) $data['price'],
            'stock' => (int) $data['stock'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $row = DB::table('custom_products')->where('uuid', $uuid)->first();
        return response()->json(['data' => $row], 201);
    }

    public function update(Request $request, string $uuid)
    {
        $data = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'category' => ['sometimes', 'nullable', 'string', 'max:255'],
            'type' => ['sometimes', 'nullable', 'string', 'max:255'],
            'image' => ['sometimes', 'nullable', 'string', 'max:255'],
            'price' => ['sometimes', 'required', 'integer', 'min:0'],
            'stock' => ['sometimes', 'required', 'integer', 'min:0'],
            'slug' => ['sometimes', 'nullable', 'string', 'max:255'],
        ]);

        $existing = DB::table('custom_products')->where('uuid', $uuid)->first();
        if (!$existing) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $nextTitle = array_key_exists('title', $data) ? $data['title'] : (string) ($existing->title ?? '');
        $nextSlug = null;
        if (array_key_exists('slug', $data) || array_key_exists('title', $data)) {
            $nextSlug = $this->ensureUniqueSlug($data['slug'] ?? null, $nextTitle, $uuid);
        }

        $update = [
            'updated_at' => now(),
        ];
        foreach (['title', 'description', 'category', 'type', 'image', 'price', 'stock'] as $key) {
            if (array_key_exists($key, $data)) {
                $update[$key] = $data[$key];
            }
        }
        if ($nextSlug !== null) {
            $update['slug'] = $nextSlug;
        }

        DB::table('custom_products')->where('uuid', $uuid)->update($update);

        $row = DB::table('custom_products')->where('uuid', $uuid)->first();
        return response()->json(['data' => $row]);
    }

    public function destroy(string $uuid)
    {
        $existing = DB::table('custom_products')->where('uuid', $uuid)->first();
        if (!$existing) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        DB::table('custom_products')->where('uuid', $uuid)->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    private function generatePublicId(): string
    {
        // Keep compatibility with existing front-end style: cust-...
        return 'cust-' . now()->format('YmdHis') . '-' . Str::lower(Str::random(6));
    }

    private function ensureUniqueSlug(?string $requested, string $title, ?string $ignoreUuid = null): string
    {
        $base = Str::slug(trim((string) ($requested ?: $title)));
        if ($base === '') $base = 'produk';

        $slug = $base;
        $i = 2;
        while (true) {
            $q = DB::table('custom_products')->where('slug', $slug);
            if ($ignoreUuid) {
                $q->where('uuid', '!=', $ignoreUuid);
            }
            $exists = $q->exists();
            if (!$exists) return $slug;

            $slug = $base . '-' . $i;
            $i++;
            if ($i > 200) {
                return $base . '-' . Str::lower(Str::random(6));
            }
        }
    }
}
