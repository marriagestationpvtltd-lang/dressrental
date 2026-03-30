<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('category_ornament_recommendations')) {
            return;
        }

        Schema::create('category_ornament_recommendations', function (Blueprint $table) {
            $table->foreignId('dress_category_id')
                ->constrained('dress_categories')
                ->cascadeOnDelete();
            $table->foreignId('ornament_id')
                ->constrained('ornaments')
                ->cascadeOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->primary(['dress_category_id', 'ornament_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_ornament_recommendations');
    }
};
