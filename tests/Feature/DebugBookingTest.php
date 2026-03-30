<?php
// Test what the store endpoint returns
namespace Tests\Feature;

// Create a test that dumps the response
use App\Models\Booking;
use App\Models\Dress;
use App\Models\DressCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DebugBookingTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_debug_booking_store(): void
    {
        $user = User::factory()->create();
        $category = DressCategory::create(['name' => 'Bridal', 'slug' => 'bridal']);
        $dress = Dress::create([
            'category_id' => $category->id,
            'name' => 'Test Dress',
            'slug' => 'test-dress',
            'size' => 'M',
            'price_per_day' => 500,
            'deposit_amount' => 1000,
            'status' => 'available',
        ]);
        
        $response = $this->actingAs($user)->post(route('bookings.store'), [
            'dress_id' => $dress->id,
            'start_date' => Carbon::today()->addDays(5)->format('Y-m-d'),
            'end_date' => Carbon::today()->addDays(7)->format('Y-m-d'),
        ]);
        
        dump($response->status());
        dump($response->getContent());
        dump(session()->all());
    }
}
