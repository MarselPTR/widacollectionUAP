<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->unique('public_id', 'reviews_public_id_unique');
                $table->unique('order_id', 'reviews_order_id_unique');

                $table->index('user_id', 'reviews_user_id_index');
                $table->index('order_id', 'reviews_order_id_index');
                $table->index('product_id', 'reviews_product_id_index');
                $table->index('email', 'reviews_email_index');
                $table->index(['product_id', 'rating'], 'reviews_product_rating_index');

                $table
                    ->foreign('user_id', 'reviews_user_id_foreign')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();

                $table
                    ->foreign('order_id', 'reviews_order_id_foreign')
                    ->references('id')
                    ->on('orders')
                    ->nullOnDelete();
            });
            return;
        }

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // Mirrors front-end review id (rev-...)
            $table->string('public_id');

            $table->foreignId('user_id')->nullable();
            $table->foreignId('order_id')->nullable();

            $table->string('product_id');
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->string('author')->nullable();
            $table->string('email')->nullable();

            $table->timestamp('reviewed_at')->useCurrent();
            $table->timestamps();

            $table->unique('public_id', 'reviews_public_id_unique');
            $table->unique('order_id', 'reviews_order_id_unique');
            $table->index('user_id', 'reviews_user_id_index');
            $table->index('order_id', 'reviews_order_id_index');
            $table->index('product_id', 'reviews_product_id_index');
            $table->index('email', 'reviews_email_index');
            $table->index(['product_id', 'rating'], 'reviews_product_rating_index');

            $table
                ->foreign('user_id', 'reviews_user_id_foreign')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table
                ->foreign('order_id', 'reviews_order_id_foreign')
                ->references('id')
                ->on('orders')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
