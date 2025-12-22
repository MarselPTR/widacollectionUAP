<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // UUID identifiers
        $this->addUuid('users', 'users_uuid_unique');
        $this->addUuid('profiles', 'profiles_uuid_unique');
        $this->addUuid('addresses', 'addresses_uuid_unique');
        $this->addUuid('wishlist_items', 'wishlist_items_uuid_unique');
        $this->addUuid('custom_products', 'custom_products_uuid_unique');
        $this->addUuid('orders', 'orders_uuid_unique');
        $this->addUuid('order_items', 'order_items_uuid_unique');
        $this->addUuid('reviews', 'reviews_uuid_unique');
        $this->addUuid('live_drop_settings', 'live_drop_settings_uuid_unique');
        $this->addUuid('contact_messages', 'contact_messages_uuid_unique');
        $this->addUuid('carts', 'carts_uuid_unique');
        $this->addUuid('cart_items', 'cart_items_uuid_unique');

        // Slug identifier (product details via URL)
        $this->addSlugToCustomProducts();
    }

    public function down(): void
    {
        $this->dropColumnIfExists('cart_items', 'uuid');
        $this->dropColumnIfExists('carts', 'uuid');
        $this->dropColumnIfExists('contact_messages', 'uuid');
        $this->dropColumnIfExists('live_drop_settings', 'uuid');
        $this->dropColumnIfExists('reviews', 'uuid');
        $this->dropColumnIfExists('order_items', 'uuid');
        $this->dropColumnIfExists('orders', 'uuid');
        $this->dropColumnIfExists('custom_products', 'uuid');
        $this->dropColumnIfExists('custom_products', 'slug');
        $this->dropColumnIfExists('wishlist_items', 'uuid');
        $this->dropColumnIfExists('addresses', 'uuid');
        $this->dropColumnIfExists('profiles', 'uuid');
        $this->dropColumnIfExists('users', 'uuid');
    }

    private function addUuid(string $table, string $uniqueIndexName): void
    {
        if (!Schema::hasTable($table)) return;

        if (!Schema::hasColumn($table, 'uuid')) {
            Schema::table($table, function (Blueprint $tableBlueprint) use ($uniqueIndexName) {
                // Keep nullable for a moment so we can backfill without DBAL.
                $tableBlueprint->char('uuid', 36)->nullable();
                $tableBlueprint->unique('uuid', $uniqueIndexName);
            });
        }

        // Backfill existing rows (MySQL UUID() returns a v1-ish UUID string; good enough for uniqueness).
        try {
            DB::statement("UPDATE `{$table}` SET `uuid` = UUID() WHERE `uuid` IS NULL");
        } catch (Throwable $e) {
            // ignore
        }

        // Enforce NOT NULL using raw SQL (avoids requiring doctrine/dbal).
        try {
            DB::statement("ALTER TABLE `{$table}` MODIFY `uuid` CHAR(36) NOT NULL");
        } catch (Throwable $e) {
            // ignore
        }
    }

    private function addSlugToCustomProducts(): void
    {
        if (!Schema::hasTable('custom_products')) return;

        if (!Schema::hasColumn('custom_products', 'slug')) {
            Schema::table('custom_products', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('public_id');
                $table->unique('slug', 'custom_products_slug_unique');
            });
        }

        // Backfill slugs based on title; ensure uniqueness.
        $rows = DB::table('custom_products')
            ->select('id', 'title', 'slug')
            ->orderBy('id')
            ->get();

        $used = DB::table('custom_products')
            ->whereNotNull('slug')
            ->pluck('slug')
            ->map(fn ($v) => strtolower((string) $v))
            ->all();
        $used = array_fill_keys($used, true);

        foreach ($rows as $row) {
            if (!empty($row->slug)) {
                $used[strtolower((string) $row->slug)] = true;
                continue;
            }

            $base = Str::slug((string) ($row->title ?? 'produk'));
            if ($base === '') $base = 'produk';
            $slug = $base;
            $i = 2;
            while (isset($used[strtolower($slug)])) {
                $slug = $base.'-'.$i;
                $i++;
                if ($i > 200) {
                    $slug = $base.'-'.Str::lower(Str::random(6));
                    break;
                }
            }
            $used[strtolower($slug)] = true;

            DB::table('custom_products')->where('id', $row->id)->update(['slug' => $slug]);
        }

        // Enforce NOT NULL for slug where possible.
        try {
            DB::statement("UPDATE `custom_products` SET `slug` = CONCAT('produk-', `id`) WHERE `slug` IS NULL OR `slug` = ''");
            DB::statement("ALTER TABLE `custom_products` MODIFY `slug` VARCHAR(255) NOT NULL");
        } catch (Throwable $e) {
            // ignore
        }
    }

    private function dropColumnIfExists(string $table, string $column): void
    {
        if (!Schema::hasTable($table)) return;
        if (!Schema::hasColumn($table, $column)) return;

        Schema::table($table, function (Blueprint $tableBlueprint) use ($column) {
            $tableBlueprint->dropColumn($column);
        });
    }
};
