<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Dress;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = now()->addDays(fake()->numberBetween(1, 7));
        $endDate   = $startDate->copy()->addDays(fake()->numberBetween(1, 6));
        $totalDays = $startDate->diffInDays($endDate) + 1;

        $pricePerDay = fake()->randomFloat(2, 100, 2000);
        $rental      = round($pricePerDay * $totalDays, 2);
        $deposit     = fake()->randomFloat(2, 500, 5000);
        $total       = $rental + $deposit;

        return [
            'user_id'        => User::factory(),
            'dress_id'       => Dress::factory(),
            'start_date'     => $startDate->format('Y-m-d'),
            'end_date'       => $endDate->format('Y-m-d'),
            'bs_start_date'  => null,
            'bs_end_date'    => null,
            'total_days'     => $totalDays,
            'rental_amount'  => $rental,
            'deposit_amount' => $deposit,
            'total_amount'   => $total,
            'advance_amount' => round($total * 0.30, 2),
            'fine_amount'    => 0,
            'status'         => 'pending',
            'notes'          => null,
            'paid_at'        => null,
            'returned_at'    => null,
        ];
    }

    /**
     * Indicate the booking has been paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'  => 'paid',
            'paid_at' => now(),
        ]);
    }

    /**
     * Indicate the booking is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate the booking has been cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    /**
     * Indicate the booking has been completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'      => 'completed',
            'returned_at' => now(),
        ]);
    }
}
