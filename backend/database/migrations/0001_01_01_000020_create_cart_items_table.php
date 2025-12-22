<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cart_items')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->index('cart_id', 'cart_items_cart_id_index');
                $table->index('product_id', 'cart_items_product_id_index');
                $table->unique(['cart_id', 'product_id'], 'cart_items_cart_product_unique');
                $table
                    ->foreign('cart_id', 'cart_items_cart_id_foreign')
                    ->references('id')
                    ->on('carts')
                    ->cascadeOnDelete();
            });
            return;
        }

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cart_id');

            // Matches existing front-end product identifier (API id or custom id like cust-...)
            $table->string('product_id')->nullable();

            $table->string('name');
            $table->string('category')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('quantity')->default(1);
            $table->text('description')->nullable();
            $table->json('raw')->nullable();

            $table->timestamps();

            $table->index('cart_id', 'cart_items_cart_id_index');
            $table->index('product_id', 'cart_items_product_id_index');
            $table->unique(['cart_id', 'product_id'], 'cart_items_cart_product_unique');
            $table
                ->foreign('cart_id', 'cart_items_cart_id_foreign')
                ->references('id')
                ->on('carts')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
