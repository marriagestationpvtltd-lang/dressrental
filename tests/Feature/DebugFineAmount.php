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

class DebugFineAmount extends TestCase
{
    use RefreshDatabase;
    public function test_fine_amount_null_error(): void
    {
        $this->withoutExceptionHandling();
        $category = DressCategory::create(['name' => 'Bridal', 'slug' => 'bridal']);
        $dress = Dress::create(['category_id' => $category->id, 'name' => 'Test', 'slug' => 'test', 'size' => 'M', 'price_per_day' => 500, 'deposit_amount' => 1000, 'status' => 'available']);
        $user = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = Booking::create(['user_id' => $user->id, 'dress_id' => $dress->id, 'start_date' => '2026-04-10', 'end_date' => '2026-04-12', 'total_days' => 3, 'rental_amount' => 1500, 'deposit_amount' => 1000, 'total_amount' => 2500, 'advance_amount' => 1250, 'status' => 'pending']);
        
        $response = $this->actingAs($admin)->put(route('admin.bookings.update', $booking), [
            'status' => 'active',
            'fine_amount' => '',
            'discount_type' => 'none',
            'discount_amount' => 0,
        ]);
    }
}
