<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('dresses')) {
            return;
        }

        Schema::create('dresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('dress_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('size', ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Free Size']);
            $table->decimal('price_per_day', 10, 2);
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->boolean('is_featured')->default(false);
            $table->string('color')->nullable();
            $table->string('brand')->nullable();
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dresses');
    }
};
