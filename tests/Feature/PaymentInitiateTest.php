<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Dress;
use App\Models\DressCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentInitiateTest extends TestCase
{
    use RefreshDatabase;

    private function createDressWithCategory(): Dress
    {
        $category = DressCategory::create(['name' => 'Bridal', 'slug' => 'bridal']);

        return Dress::create([
            'category_id'    => $category->id,
            'name'           => 'Red Bridal Dress',
            'slug'           => 'red-bridal-dress',
            'size'           => 'M',
            'price_per_day'  => 500,
            'deposit_amount' => 1000,
            'status'         => 'available',
        ]);
    }

    private function createPendingBooking(User $user, Dress $dress): Booking
    {
        return Booking::create([
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
    }

    /**
     * The core flow: after booking creation the owner can access the payment page.
     * This would previously return 403 due to a strict type comparison (user_id
     * returned as string by some DB drivers versus integer auth()->id()).
     */
    public function test_booking_owner_can_access_payment_initiate_page(): void
    {
        $user    = User::factory()->create();
        $dress   = $this->createDressWithCategory();
        $booking = $this->createPendingBooking($user, $dress);

        $response = $this->actingAs($user)
            ->get(route('payment.initiate', $booking));

        $response->assertStatus(200);
    }

    /**
     * Completing the booking store flow redirects directly to the payment page
     * without triggering a 403.
     */
    public function test_booking_store_redirects_to_payment_initiate_and_page_is_accessible(): void
    {
        $user  = User::factory()->create();
        $dress = $this->createDressWithCategory();

        // Step 1: create the booking via the store endpoint
        $storeResponse = $this->actingAs($user)->post(route('bookings.store'), [
            'dress_id'   => $dress->id,
            'start_date' => Carbon::today()->addDays(5)->format('Y-m-d'),
            'end_date'   => Carbon::today()->addDays(7)->format('Y-m-d'),
        ]);

        $booking = Booking::where('user_id', $user->id)->firstOrFail();

        // Step 2: the redirect must point to the correct payment page
        $storeResponse->assertRedirectToRoute('payment.initiate', $booking);

        // Step 3: following the redirect must succeed (no 403)
        $paymentResponse = $this->actingAs($user)
            ->get(route('payment.initiate', $booking));

        $paymentResponse->assertStatus(200);
    }

    /**
     * A different authenticated user must NOT be able to access another user's
     * payment initiate page — they should receive a 403.
     */
    public function test_other_user_cannot_access_payment_initiate_page(): void
    {
        $owner   = User::factory()->create();
        $other   = User::factory()->create();
        $dress   = $this->createDressWithCategory();
        $booking = $this->createPendingBooking($owner, $dress);

        $response = $this->actingAs($other)
            ->get(route('payment.initiate', $booking));

        $response->assertStatus(403);
    }

    /**
     * An unauthenticated user is redirected to the login page when accessing
     * the payment initiate page.
     */
    public function test_guest_is_redirected_to_login_from_payment_initiate_page(): void
    {
        $user    = User::factory()->create();
        $dress   = $this->createDressWithCategory();
        $booking = $this->createPendingBooking($user, $dress);

        $response = $this->get(route('payment.initiate', $booking));

        $response->assertRedirectToRoute('login');
    }

    /**
     * When the booking status is no longer pending the owner is redirected away
     * from the payment initiate page with an informational message — NOT a 403.
     */
    public function test_non_pending_booking_redirects_to_booking_show_page(): void
    {
        $user    = User::factory()->create();
        $dress   = $this->createDressWithCategory();
        $booking = $this->createPendingBooking($user, $dress);
        $booking->update(['status' => 'paid']);

        $response = $this->actingAs($user)
            ->get(route('payment.initiate', $booking));

        $response->assertRedirectToRoute('bookings.show', $booking);
        $response->assertSessionHas('info');
    }

    /**
     * The user_id attribute on a Booking model is always returned as an integer,
     * ensuring the strict !== comparison against auth()->id() never creates a
     * type-mismatch false positive.
     */
    public function test_booking_user_id_is_cast_to_integer(): void
    {
        $user    = User::factory()->create();
        $dress   = $this->createDressWithCategory();
        $booking = $this->createPendingBooking($user, $dress);

        // Reload from DB to ensure we get the casted value, not just what was set
        $booking = Booking::find($booking->id);

        $this->assertIsInt($booking->user_id);
        $this->assertSame((int) $user->id, $booking->user_id);
    }
}
