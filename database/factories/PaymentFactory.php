<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_id'       => Booking::factory(),
            'user_id'          => User::factory(),
            'amount'           => fake()->randomFloat(2, 100, 5000),
            'payment_method'   => fake()->randomElement(['esewa', 'khalti', 'cash']),
            'transaction_id'   => Str::uuid()->toString(),
            'status'           => 'completed',
            'payment_type'     => 'advance',
            'gateway_response' => null,
            'remarks'          => null,
            'verified_at'      => now(),
        ];
    }

    /**
     * Indicate the payment is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'      => 'pending',
            'verified_at' => null,
        ]);
    }

    /**
     * Indicate the payment has failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'      => 'failed',
            'verified_at' => null,
        ]);
    }

    /**
     * Indicate this is a balance payment.
     */
    public function balance(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_type' => 'balance',
        ]);
    }
}
