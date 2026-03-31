<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'discount_type')) {
                $table->enum('discount_type', ['none', 'fixed', 'percentage'])->default('none')->after('fine_amount');
            }
            // Use 'fine_amount' as anchor when 'discount_type' may not yet exist.
            if (! Schema::hasColumn('bookings', 'discount_amount')) {
                $afterCol = Schema::hasColumn('bookings', 'discount_type') ? 'discount_type' : 'fine_amount';
                $table->decimal('discount_amount', 10, 2)->default(0)->after($afterCol);
            }
        });
    }

    public function down(): void
    {
        $toDrop = array_filter(
            ['discount_type', 'discount_amount'],
            fn ($col) => Schema::hasColumn('bookings', $col)
        );

        if (! empty($toDrop)) {
            Schema::table('bookings', function (Blueprint $table) use ($toDrop) {
                $table->dropColumn(array_values($toDrop));
            });
        }
    }
};
