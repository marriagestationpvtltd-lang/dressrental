<?php

namespace Tests\Feature;

use App\Mail\BookingConfirmation;
use App\Mail\BookingStatusUpdated;
use App\Models\Booking;
use App\Models\Dress;
use App\Models\DressCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BookingEmailTest extends TestCase
{
    use RefreshDatabase;

    private function createDressWithCategory(): Dress
    {
        $category = DressCategory::create(['name' => 'Bridal', 'slug' => 'bridal']);

        return Dress::create([
            'category_id'   => $category->id,
            'name'          => 'Red Bridal Dress',
            'slug'          => 'red-bridal-dress',
            'size'          => 'M',
            'price_per_day' => 500,
            'deposit_amount'=> 1000,
            'status'        => 'available',
        ]);
    }

    private function createBookingForUser(User $user, Dress $dress): Booking
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

    public function test_booking_confirmation_email_is_sent_on_booking_creation(): void
    {
        Mail::fake();

        $user  = User::factory()->create(['email' => 'test@example.com']);
        $dress = $this->createDressWithCategory();

        $this->actingAs($user)->post(route('bookings.store'), [
            'dress_id'   => $dress->id,
            'start_date' => Carbon::today()->addDays(5)->format('Y-m-d'),
            'end_date'   => Carbon::today()->addDays(7)->format('Y-m-d'),
        ]);

        Mail::assertSent(BookingConfirmation::class, function (BookingConfirmation $mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_booking_confirmation_email_has_correct_subject(): void
    {
        Mail::fake();

        $user  = User::factory()->create();
        $dress = $this->createDressWithCategory();

        $this->actingAs($user)->post(route('bookings.store'), [
            'dress_id'   => $dress->id,
            'start_date' => Carbon::today()->addDays(5)->format('Y-m-d'),
            'end_date'   => Carbon::today()->addDays(7)->format('Y-m-d'),
        ]);

        Mail::assertSent(BookingConfirmation::class, function (BookingConfirmation $mail) {
            return str_contains($mail->envelope()->subject, 'Booking Confirmation');
        });
    }

    public function test_status_update_email_is_sent_when_admin_updates_status(): void
    {
        Mail::fake();

        $admin   = User::factory()->create(['role' => 'admin']);
        $user    = User::factory()->create(['email' => 'customer@example.com']);
        $dress   = $this->createDressWithCategory();
        $booking = $this->createBookingForUser($user, $dress);

        $this->actingAs($admin)->post(route('admin.bookings.update-status', $booking), [
            'status' => 'active',
        ]);

        Mail::assertSent(BookingStatusUpdated::class, function (BookingStatusUpdated $mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_status_update_email_is_not_sent_when_status_unchanged(): void
    {
        Mail::fake();

        $admin   = User::factory()->create(['role' => 'admin']);
        $user    = User::factory()->create();
        $dress   = $this->createDressWithCategory();
        $booking = $this->createBookingForUser($user, $dress);

        // Post the same status (pending → pending)
        $this->actingAs($admin)->post(route('admin.bookings.update-status', $booking), [
            'status' => 'pending',
        ]);

        Mail::assertNotSent(BookingStatusUpdated::class);
    }

    public function test_status_update_email_is_sent_when_admin_uses_update_route(): void
    {
        Mail::fake();

        $admin   = User::factory()->create(['role' => 'admin']);
        $user    = User::factory()->create(['email' => 'customer2@example.com']);
        $dress   = $this->createDressWithCategory();
        $booking = $this->createBookingForUser($user, $dress);

        $this->actingAs($admin)->put(route('admin.bookings.update', $booking), [
            'status'      => 'paid',
            'notes'       => 'Advance payment received.',
            'fine_amount' => 0,
        ]);

        Mail::assertSent(BookingStatusUpdated::class, function (BookingStatusUpdated $mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_status_update_email_has_correct_subject(): void
    {
        Mail::fake();

        $admin   = User::factory()->create(['role' => 'admin']);
        $user    = User::factory()->create();
        $dress   = $this->createDressWithCategory();
        $booking = $this->createBookingForUser($user, $dress);

        $this->actingAs($admin)->post(route('admin.bookings.update-status', $booking), [
            'status' => 'active',
        ]);

        Mail::assertSent(BookingStatusUpdated::class, function (BookingStatusUpdated $mail) use ($booking) {
            return str_contains($mail->envelope()->subject, 'Booking Update')
                && str_contains($mail->envelope()->subject, (string) $booking->id);
        });
    }

    public function test_booking_confirmation_mailable_uses_correct_view(): void
    {
        $user    = User::factory()->create();
        $dress   = $this->createDressWithCategory();
        $booking = $this->createBookingForUser($user, $dress);
        $booking->load(['dress', 'user']);

        $mailable = new BookingConfirmation($booking);

        $mailable->assertSeeInHtml($user->name);
        $mailable->assertSeeInHtml($dress->name);
        $mailable->assertSeeInHtml((string) $booking->id);
    }

    public function test_booking_status_updated_mailable_uses_correct_view(): void
    {
        $user    = User::factory()->create();
        $dress   = $this->createDressWithCategory();
        $booking = $this->createBookingForUser($user, $dress);
        $booking->load(['dress', 'user']);

        $mailable = new BookingStatusUpdated($booking, 'pending');

        $mailable->assertSeeInHtml($user->name);
        $mailable->assertSeeInHtml($dress->name);
        $mailable->assertSeeInHtml('pending');
        $mailable->assertSeeInHtml('Pending');
    }
}
