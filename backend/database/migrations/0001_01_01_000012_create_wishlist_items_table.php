<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('wishlist_items')) {
            Schema::table('wishlist_items', function (Blueprint $table) {
                $table->index('user_id', 'wishlist_items_user_id_index');
                $table->index('product_id', 'wishlist_items_product_id_index');
                $table->unique(['user_id', 'product_id'], 'wishlist_items_user_product_unique');
                $table
                    ->foreign('user_id', 'wishlist_items_user_id_foreign')
                    ->references('id')
                    ->on('users')
                    ->cascadeOnDelete();
            });
            return;
        }

        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');

            // Matches front-end's product identifier (API id or custom id like cust-...)
            $table->string('product_id')->nullable();
            $table->string('title');
            $table->string('image')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->string('category')->nullable();
            $table->string('badge')->nullable();

            // Mirrors localStorage wishlist item's createdAt
            $table->timestamp('added_at')->useCurrent();

            $table->timestamps();

            $table->index('user_id', 'wishlist_items_user_id_index');
            $table->index('product_id', 'wishlist_items_product_id_index');
            $table->unique(['user_id', 'product_id'], 'wishlist_items_user_product_unique');
            $table
                ->foreign('user_id', 'wishlist_items_user_id_foreign')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlist_items');
    }
};
