<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Dress;
use App\Models\DressCategory;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPaymentUpdateTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function createBookingWithPayment(): array
    {
        $category = DressCategory::create(['name' => 'Bridal', 'slug' => 'bridal']);
        $dress = Dress::create([
            'category_id'    => $category->id,
            'name'           => 'Red Bridal Dress',
            'slug'           => 'red-bridal-dress',
            'size'           => 'M',
            'price_per_day'  => 500,
            'deposit_amount' => 1000,
            'status'         => 'available',
        ]);
        $user = User::factory()->create();
        $booking = Booking::create([
            'user_id'        => $user->id,
            'dress_id'       => $dress->id,
            'start_date'     => Carbon::today()->addDays(5)->format('Y-m-d'),
            'end_date'       => Carbon::today()->addDays(7)->format('Y-m-d'),
            'total_days'     => 3,
            'rental_amount'  => 1500.00,
            'deposit_amount' => 1000.00,
            'total_amount'   => 2500.00,
            'advance_amount' => 1250.00,
            'status'         => 'pending',
        ]);
        $payment = Payment::create([
            'booking_id'     => $booking->id,
            'user_id'        => $user->id,
            'amount'         => 1250.00,
            'payment_method' => 'cash',
            'status'         => 'pending',
            'payment_type'   => 'advance',
        ]);
        return [$booking, $payment, $user];
    }

    public function test_admin_can_update_payment_status(): void
    {
        $admin = $this->createAdmin();
        [$booking, $payment] = $this->createBookingWithPayment();

        $response = $this->actingAs($admin)->put(route('admin.payments.update', $payment), [
            'status'         => 'completed',
            'payment_method' => 'cash',
            'amount'         => 1250.00,
            'transaction_id' => null,
            'remarks'        => 'Payment verified',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'status' => 'completed']);
    }

    public function test_admin_can_update_payment_method(): void
    {
        $admin = $this->createAdmin();
        [$booking, $payment] = $this->createBookingWithPayment();

        $response = $this->actingAs($admin)->put(route('admin.payments.update', $payment), [
            'status'         => 'pending',
            'payment_method' => 'esewa',
            'amount'         => 1250.00,
            'transaction_id' => 'TXN123',
            'remarks'        => null,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'payment_method' => 'esewa']);
    }

    public function test_payment_completion_syncs_booking_status(): void
    {
        $admin = $this->createAdmin();
        [$booking, $payment] = $this->createBookingWithPayment();

        $this->actingAs($admin)->put(route('admin.payments.update', $payment), [
            'status'         => 'completed',
            'payment_method' => 'cash',
            'amount'         => 1250.00,
            'transaction_id' => null,
            'remarks'        => null,
        ]);

        // Booking should be marked as paid when advance payment is completed
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'paid']);
    }

    public function test_admin_can_update_booking_from_show_page(): void
    {
        $admin = $this->createAdmin();
        [$booking, $payment] = $this->createBookingWithPayment();

        $response = $this->actingAs($admin)->put(route('admin.bookings.update', $booking), [
            'status'          => 'active',
            'notes'           => 'Test note',
            'fine_amount'     => 0,
            'discount_type'   => 'none',
            'discount_amount' => 0,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'active']);
    }

    public function test_admin_can_update_booking_with_discount(): void
    {
        $admin = $this->createAdmin();
        [$booking, $payment] = $this->createBookingWithPayment();

        $response = $this->actingAs($admin)->put(route('admin.bookings.update', $booking), [
            'status'          => 'pending',
            'notes'           => null,
            'fine_amount'     => 500,
            'discount_type'   => 'fixed',
            'discount_amount' => 200,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'discount_type' => 'fixed']);
    }

    public function test_admin_can_update_booking_without_fine_amount(): void
    {
        // Edge case: fine_amount submitted as empty (becomes null via ConvertEmptyStringsToNull)
        $admin = $this->createAdmin();
        [$booking, $payment] = $this->createBookingWithPayment();

        $response = $this->actingAs($admin)->put(route('admin.bookings.update', $booking), [
            'status'          => 'active',
            'notes'           => null,
            'fine_amount'     => '',  // empty string → null via middleware
            'discount_type'   => 'none',
            'discount_amount' => 0,
        ]);

        // Should not 500 - should either succeed or show validation error
        $response->assertRedirect();
        $this->assertNotEquals(500, $response->status());
    }
}
