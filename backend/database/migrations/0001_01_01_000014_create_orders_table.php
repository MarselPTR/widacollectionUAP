<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unique('public_id', 'orders_public_id_unique');

                $table->index('user_id', 'orders_user_id_index');
                $table->index('product_id', 'orders_product_id_index');
                $table->index(['user_id', 'status'], 'orders_user_status_index');
                $table->index(['placed_at'], 'orders_placed_at_index');

                $table
                    ->foreign('user_id', 'orders_user_id_foreign')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            });
            return;
        }

        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable();

            // Mirrors front-end order id (e.g. WCxxxxxx)
            $table->string('public_id');

            $table->string('product_id')->nullable();
            $table->string('product_title')->nullable();
            $table->string('product_image')->nullable();

            $table->enum('status', ['packed', 'shipped', 'delivered'])->default('packed');
            $table->string('status_note')->nullable();

            $table->string('shipping_label')->nullable();
            $table->char('currency', 3)->default('IDR');
            $table->unsignedInteger('total')->default(0);

            $table->timestamp('placed_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            // Snapshot of customer fields from checkout
            $table->json('customer_snapshot')->nullable();

            $table->timestamps();

            $table->unique('public_id', 'orders_public_id_unique');
            $table->index('user_id', 'orders_user_id_index');
            $table->index('product_id', 'orders_product_id_index');
            $table->index(['user_id', 'status'], 'orders_user_status_index');
            $table->index(['placed_at'], 'orders_placed_at_index');
            $table
                ->foreign('user_id', 'orders_user_id_foreign')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
