<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();

            // Mirrors front-end message id (msg-...)
            $table->string('public_id')->unique();

            $table->string('name');
            $table->string('email');
            $table->string('subject')->nullable();
            $table->text('message');

            // Optional: email of logged-in user who submitted
            $table->string('user_email')->nullable()->index();

            $table->string('source')->nullable();
            $table->timestamp('submitted_at')->useCurrent();

            $table->timestamps();

            $table->index(['email', 'submitted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
