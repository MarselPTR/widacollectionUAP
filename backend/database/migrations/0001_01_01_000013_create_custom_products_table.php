<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_products', function (Blueprint $table) {
            $table->id();

            // Public identifier used by the existing front-end (cust-...)
            $table->string('public_id')->unique();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('type')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('stock')->default(0);

            $table->timestamps();

            $table->index(['category', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_products');
    }
};
