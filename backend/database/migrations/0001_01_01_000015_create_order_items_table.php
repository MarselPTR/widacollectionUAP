<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->index('order_id', 'order_items_order_id_index');
                $table->index('product_id', 'order_items_product_id_index');
                $table
                    ->foreign('order_id', 'order_items_order_id_foreign')
                    ->references('id')
                    ->on('orders')
                    ->cascadeOnDelete();
            });
            return;
        }

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id');

            $table->string('product_id')->nullable();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('quantity')->default(1);
            $table->text('note')->nullable();
            $table->json('raw')->nullable();

            $table->timestamps();

            $table->index('order_id', 'order_items_order_id_index');
            $table->index('product_id', 'order_items_product_id_index');
            $table
                ->foreign('order_id', 'order_items_order_id_foreign')
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
