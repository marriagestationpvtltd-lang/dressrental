<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dress_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dress_id')->constrained('dresses')->onDelete('cascade');
            $table->unsignedSmallInteger('days');
            $table->decimal('price', 10, 2);
            $table->unique(['dress_id', 'days']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dress_pricings');
    }
};
