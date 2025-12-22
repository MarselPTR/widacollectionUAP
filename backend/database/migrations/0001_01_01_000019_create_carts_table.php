<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('carts')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->unique('public_id', 'carts_public_id_unique');
                $table->index('user_id', 'carts_user_id_index');
                $table->index('session_id', 'carts_session_id_index');
                $table->index(['user_id', 'updated_at'], 'carts_user_updated_index');
                $table->index(['session_id', 'updated_at'], 'carts_session_updated_index');
                $table
                    ->foreign('user_id', 'carts_user_id_foreign')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            });
            return;
        }

        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            // Public identifier to reference cart safely from the front-end.
            $table->string('public_id');

            // Nullable so guests can have a cart by session.
            $table->foreignId('user_id')->nullable();

            // For guest carts (or even logged-in carts), bind to a browser session key.
            $table->string('session_id')->nullable();

            $table->char('currency', 3)->default('IDR');

            // Mirrors front-end shipping preference (value is typically numeric cost).
            $table->unsignedInteger('shipping_value')->nullable();
            $table->string('shipping_label')->nullable();

            $table->timestamps();

            $table->unique('public_id', 'carts_public_id_unique');
            $table->index('user_id', 'carts_user_id_index');
            $table->index('session_id', 'carts_session_id_index');
            $table->index(['user_id', 'updated_at'], 'carts_user_updated_index');
            $table->index(['session_id', 'updated_at'], 'carts_session_updated_index');
            $table
                ->foreign('user_id', 'carts_user_id_foreign')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
