<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create dress_sizes pivot table
        Schema::create('dress_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dress_id')->constrained('dresses')->onDelete('cascade');
            $table->enum('size', ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Free Size']);
            $table->unique(['dress_id', 'size']);
            $table->timestamps();
        });

        // Migrate existing single-size values into dress_sizes
        DB::table('dresses')->whereNotNull('size')->orderBy('id')->each(function ($dress) {
            DB::table('dress_sizes')->insert([
                'dress_id'   => $dress->id,
                'size'       => $dress->size,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        // Make dresses.size nullable (kept for backward-compatibility / legacy reads)
        Schema::table('dresses', function (Blueprint $table) {
            $table->enum('size', ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Free Size'])->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dress_sizes');

        Schema::table('dresses', function (Blueprint $table) {
            $table->enum('size', ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Free Size'])->nullable(false)->change();
        });
    }
};
