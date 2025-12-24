<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Check if products exist to avoid duplicates if run multiple times without fresh
        if (DB::table('custom_products')->count() > 0) {
            return;
        }

        $products = [
            [
                'title' => 'Vintage Nike Hoodie Navy',
                'category' => 'Mens Clothing',
                'type' => 'Jacket',
                'price' => 150000,
                'image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=500&q=80',
                'description' => 'Original vintage Nike hoodie, good condition, size L.',
            ],
            [
                'title' => 'Levi\'s 501 Original Jeans',
                'category' => 'Mens Clothing',
                'type' => 'Jeans',
                'price' => 250000,
                'image' => 'https://images.unsplash.com/photo-1542272454315-4c01d7abdf4a?auto=format&fit=crop&w=500&q=80',
                'description' => 'Classic 501 jeans, blue wash, size 32.',
            ],
            [
                'title' => 'Floral Summer Dress',
                'category' => 'Womens Clothing',
                'type' => 'Dresses',
                'price' => 120000,
                'image' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=500&q=80',
                'description' => 'Beautiful floral pattern dress, lightweight material.',
            ],
            [
                'title' => 'Adidas Track Jacket Black',
                'category' => 'Mens Clothing',
                'type' => 'Jacket',
                'price' => 180000,
                'image' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=500&q=80',
                'description' => 'Classic 3-stripes track jacket, vintage vibe.',
            ],
            [
                'title' => 'Vintage Flannel Shirt',
                'category' => 'Mens Clothing',
                'type' => 'T-Shirts', // Mapping loose
                'price' => 95000,
                'image' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=500&q=80',
                'description' => 'Red and black flannel shirt, comfortable cotton.',
            ],
            [
                'title' => 'Leather Biker Jacket',
                'category' => 'Womens Clothing',
                'type' => 'Jacket',
                'price' => 350000,
                'image' => 'https://images.unsplash.com/photo-1551028919-ac66e624ec9d?auto=format&fit=crop&w=500&q=80',
                'description' => 'Genuine leather jacket, biker style, size M.',
            ],
            [
                'title' => 'Oversized T-Shirt Graphic',
                'category' => 'Mens Clothing',
                'type' => 'T-Shirts',
                'price' => 75000,
                'image' => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?auto=format&fit=crop&w=500&q=80',
                'description' => 'Vintage band tee graphic, washed black.',
            ],
            [
                'title' => 'Denim Skirt High Waist',
                'category' => 'Womens Clothing',
                'type' => 'Skirts',
                'price' => 110000,
                'image' => 'https://images.unsplash.com/photo-1582142327262-368172c74d6c?auto=format&fit=crop&w=500&q=80',
                'description' => 'Classic denim skirt, A-line cut.',
            ],
            [
                'title' => 'Corduroy Pants Brown',
                'category' => 'Mens Clothing',
                'type' => 'Pants',
                'price' => 130000,
                'image' => 'https://images.unsplash.com/photo-1517445312882-566d3d9200a7?auto=format&fit=crop&w=500&q=80',
                'description' => 'Vintage corduroy trousers, loose fit.',
            ],
            [
                'title' => 'Knitted Cardigan Beige',
                'category' => 'Womens Clothing',
                'type' => 'Sweaters',
                'price' => 140000,
                'image' => 'https://images.unsplash.com/photo-1620799140408-ed5341cd2431?auto=format&fit=crop&w=500&q=80',
                'description' => 'Soft knit cardigan, perfect for layering.',
            ],
        ];

        $now = now();

        foreach ($products as $p) {
            $uuid = (string) Str::uuid();
            $publicId = 'prod-' . strtolower(Str::random(8));
            $baseSlug = Str::slug($p['title']);
            $slug = $baseSlug . '-' . strtolower(Str::random(4));

            DB::table('custom_products')->insert([
                'uuid' => $uuid,
                'public_id' => $publicId,
                'slug' => $slug,
                'title' => $p['title'],
                'description' => $p['description'],
                'category' => $p['category'],
                'type' => $p['type'],
                'image' => $p['image'],
                'price' => $p['price'],
                'stock' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
