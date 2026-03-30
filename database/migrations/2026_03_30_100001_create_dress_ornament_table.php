<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('dress_ornament')) {
            return;
        }

        Schema::create('dress_ornament', function (Blueprint $table) {
            $table->foreignId('dress_id')->constrained('dresses')->onDelete('cascade');
            $table->foreignId('ornament_id')->constrained('ornaments')->onDelete('cascade');
            $table->primary(['dress_id', 'ornament_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dress_ornament');
    }
};
