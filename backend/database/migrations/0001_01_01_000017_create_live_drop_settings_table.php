<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_drop_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('event_title')->nullable();
            $table->string('event_subtitle')->nullable();
            $table->dateTime('event_date_time')->nullable();
            $table->string('cta_label')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_drop_settings');
    }
};
