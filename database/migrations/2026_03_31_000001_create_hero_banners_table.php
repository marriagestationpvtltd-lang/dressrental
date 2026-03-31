<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('hero_banners')) {
            return;
        }

        Schema::create('hero_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('media_type')->default('image'); // image | youtube
            $table->string('media_value');                  // storage path for image, YouTube video ID for youtube
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_banners');
    }
};
