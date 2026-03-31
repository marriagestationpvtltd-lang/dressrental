<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('booking_ornament')) {
            return;
        }

        Schema::create('booking_ornament', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('ornament_id')->constrained('ornaments')->cascadeOnDelete();
            $table->decimal('price_per_day', 10, 2);
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();

            $table->unique(['booking_id', 'ornament_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_ornament');
    }
};
