<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Dress;
use App\Models\DressCategory;
use App\Models\DressImage;
use App\Models\Ornament;
use App\Models\Page;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\TestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Verifies that every database migration runs correctly and that the resulting
 * schema, model CRUD operations, and relationships all work as expected.
 *
 * The TestSeeder is also exercised as an integration sanity-check.
 */
class DatabaseMigrationTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================
    // Schema Verification
    // =========================================================

    public function test_users_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasColumns('users', [
            'id', 'name', 'email', 'email_verified_at', 'password',
            'phone', 'address', 'role', 'profile_photo', 'google_id',
            'remember_token', 'created_at', 'updated_at',
        ]));
    }

    public function test_dress_categories_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('dress_categories'));
        $this->assertTrue(Schema::hasColumns('dress_categories', [
            'id', 'name', 'slug', 'description', 'icon',
            'is_active', 'sort_order', 'parent_id', 'created_at', 'updated_at',
        ]));
    }

    public function test_dresses_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('dresses'));
        $this->assertTrue(Schema::hasColumns('dresses', [
            'id', 'category_id', 'name', 'slug', 'description', 'size',
            'price_per_day', 'deposit_amount', 'status', 'is_featured',
            'color', 'brand', 'views', 'created_at', 'updated_at',
        ]));
    }

    public function test_dress_images_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('dress_images'));
        $this->assertTrue(Schema::hasColumns('dress_images', [
            'id', 'dress_id', 'image_path', 'is_primary', 'sort_order',
            'created_at', 'updated_at',
        ]));
    }

    public function test_bookings_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('bookings'));
        $this->assertTrue(Schema::hasColumns('bookings', [
            'id', 'user_id', 'dress_id', 'start_date', 'end_date',
            'bs_start_date', 'bs_end_date', 'total_days',
            'rental_amount', 'deposit_amount', 'total_amount',
            'advance_amount', 'fine_amount', 'status', 'notes',
            'paid_at', 'returned_at', 'created_at', 'updated_at',
        ]));
    }

    public function test_payments_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('payments'));
        $this->assertTrue(Schema::hasColumns('payments', [
            'id', 'booking_id', 'user_id', 'amount', 'payment_method',
            'transaction_id', 'status', 'payment_type', 'gateway_response',
            'remarks', 'verified_at', 'created_at', 'updated_at',
        ]));
    }

    public function test_settings_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('settings'));
        $this->assertTrue(Schema::hasColumns('settings', [
            'id', 'key', 'value', 'type', 'group', 'label', 'description',
            'created_at', 'updated_at',
        ]));
    }

    public function test_hero_banners_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('hero_banners'));
        $this->assertTrue(Schema::hasColumns('hero_banners', [
            'id', 'title', 'media_type', 'media_value', 'sort_order',
            'is_active', 'created_at', 'updated_at',
        ]));
    }

    public function test_pages_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('pages'));
        $this->assertTrue(Schema::hasColumns('pages', [
            'id', 'title', 'slug', 'content', 'status', 'sort_order',
            'created_at', 'updated_at',
        ]));
    }

    public function test_ornaments_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('ornaments'));
        $this->assertTrue(Schema::hasColumns('ornaments', [
            'id', 'name', 'slug', 'description', 'price_per_day',
            'deposit_amount', 'category', 'image_path', 'status',
            'created_at', 'updated_at',
        ]));
    }

    public function test_dress_ornament_pivot_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('dress_ornament'));
        $this->assertTrue(Schema::hasColumns('dress_ornament', ['dress_id', 'ornament_id']));
    }

    public function test_sessions_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('sessions'));
    }

    public function test_cache_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('cache'));
    }

    public function test_jobs_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('jobs'));
    }

    // =========================================================
    // User CRUD
    // =========================================================

    public function test_can_create_and_read_user(): void
    {
        $user = User::factory()->create([
            'name'  => 'Sita Sharma',
            'email' => 'sita@example.com',
            'role'  => 'user',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'sita@example.com']);
        $found = User::find($user->id);
        $this->assertEquals('Sita Sharma', $found->name);
        $this->assertEquals('user', $found->role);
    }

    public function test_can_create_admin_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($admin->isAdmin());
        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin']);
    }

    public function test_can_update_user(): void
    {
        $user = User::factory()->create(['name' => 'Old Name']);
        $user->update(['name' => 'New Name', 'phone' => '9800000001']);

        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'name'  => 'New Name',
            'phone' => '9800000001',
        ]);
    }

    public function test_can_delete_user(): void
    {
        $user = User::factory()->create();
        $id   = $user->id;
        $user->delete();

        $this->assertDatabaseMissing('users', ['id' => $id]);
    }

    // =========================================================
    // DressCategory CRUD
    // =========================================================

    public function test_can_create_and_read_dress_category(): void
    {
        $category = DressCategory::factory()->create(['name' => 'Bridal Wear']);

        $this->assertDatabaseHas('dress_categories', ['name' => 'Bridal Wear']);
        $found = DressCategory::find($category->id);
        $this->assertEquals('Bridal Wear', $found->name);
        $this->assertTrue($found->is_active);
    }

    public function test_dress_category_supports_parent_child_hierarchy(): void
    {
        $parent = DressCategory::factory()->create(['name' => 'Wedding Collection']);
        $child  = DressCategory::factory()->childOf($parent)->create(['name' => 'Bridal Wear']);

        $this->assertEquals($parent->id, $child->parent->id);
        $this->assertCount(1, $parent->subcategories);
        $this->assertTrue($parent->isTopLevel());
        $this->assertFalse($child->isTopLevel());
    }

    public function test_top_level_scope_returns_only_root_categories(): void
    {
        $parent = DressCategory::factory()->create();
        DressCategory::factory()->childOf($parent)->create();

        $this->assertCount(1, DressCategory::topLevel()->get());
    }

    // =========================================================
    // Dress CRUD
    // =========================================================

    public function test_can_create_and_read_dress(): void
    {
        $dress = Dress::factory()->create([
            'name'          => 'Golden Lehenga',
            'price_per_day' => 800.00,
            'size'          => 'M',
        ]);

        $this->assertDatabaseHas('dresses', ['name' => 'Golden Lehenga', 'size' => 'M']);
        $this->assertNotEmpty($dress->slug);
        $this->assertEquals('800.00', $dress->price_per_day);
    }

    public function test_dress_slug_is_auto_generated_from_name(): void
    {
        $dress = Dress::factory()->create(['name' => 'Red Saree']);

        $this->assertNotEmpty($dress->slug);
        $this->assertStringContainsString('red-saree', $dress->slug);
    }

    public function test_can_update_dress_status(): void
    {
        $dress = Dress::factory()->create(['status' => 'available']);
        $dress->update(['status' => 'unavailable']);

        $this->assertDatabaseHas('dresses', ['id' => $dress->id, 'status' => 'unavailable']);
    }

    public function test_featured_scope_returns_only_featured_dresses(): void
    {
        Dress::factory()->count(3)->create(['is_featured' => false]);
        Dress::factory()->count(2)->featured()->create();

        $this->assertCount(2, Dress::featured()->get());
    }

    public function test_available_scope_returns_only_available_dresses(): void
    {
        Dress::factory()->count(3)->create(['status' => 'available']);
        Dress::factory()->count(2)->unavailable()->create();

        $this->assertCount(3, Dress::available()->get());
    }

    // =========================================================
    // DressImage CRUD
    // =========================================================

    public function test_can_create_primary_dress_image(): void
    {
        $image = DressImage::factory()->primary()->create();

        $this->assertDatabaseHas('dress_images', ['id' => $image->id, 'is_primary' => true]);
    }

    public function test_can_create_non_primary_dress_image(): void
    {
        $image = DressImage::factory()->create(['is_primary' => false]);

        $this->assertFalse($image->is_primary);
    }

    // =========================================================
    // Ornament CRUD
    // =========================================================

    public function test_can_create_and_read_ornament(): void
    {
        $ornament = Ornament::factory()->create([
            'name'     => 'Gold Necklace',
            'category' => 'jewelry',
        ]);

        $this->assertDatabaseHas('ornaments', ['name' => 'Gold Necklace', 'category' => 'jewelry']);
        $this->assertNotEmpty($ornament->slug);
    }

    public function test_ornament_slug_is_auto_generated_from_name(): void
    {
        $ornament = Ornament::factory()->create(['name' => 'Silver Bracelet']);

        $this->assertStringContainsString('silver-bracelet', $ornament->slug);
    }

    public function test_ornament_available_scope(): void
    {
        Ornament::factory()->count(3)->create(['status' => 'available']);
        Ornament::factory()->count(2)->unavailable()->create();

        $this->assertCount(3, Ornament::available()->get());
    }

    // =========================================================
    // Booking CRUD
    // =========================================================

    public function test_can_create_and_read_booking(): void
    {
        $booking = Booking::factory()->create([
            'status'        => 'pending',
            'total_days'    => 3,
            'rental_amount' => 1500.00,
        ]);

        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'pending']);
        $this->assertEquals('1500.00', $booking->rental_amount);
    }

    public function test_booking_status_can_be_updated(): void
    {
        $booking = Booking::factory()->create(['status' => 'pending']);
        $booking->update(['status' => 'paid', 'paid_at' => now()]);

        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'paid']);
        $this->assertNotNull($booking->fresh()->paid_at);
    }

    public function test_booking_active_scope(): void
    {
        Booking::factory()->active()->create();
        Booking::factory()->cancelled()->create();
        Booking::factory()->create(['status' => 'pending']);

        $this->assertCount(1, Booking::active()->get());
    }

    public function test_booking_pending_scope(): void
    {
        Booking::factory()->count(2)->create(['status' => 'pending']);
        Booking::factory()->paid()->create();

        $this->assertCount(2, Booking::pending()->get());
    }

    // =========================================================
    // Payment CRUD
    // =========================================================

    public function test_can_create_and_read_payment(): void
    {
        $user    = User::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $user->id]);

        $payment = Payment::factory()->create([
            'booking_id'     => $booking->id,
            'user_id'        => $user->id,
            'amount'         => 750.00,
            'payment_method' => 'esewa',
            'payment_type'   => 'advance',
        ]);

        $this->assertDatabaseHas('payments', [
            'id'             => $payment->id,
            'payment_method' => 'esewa',
            'payment_type'   => 'advance',
        ]);
        $this->assertEquals('750.00', $payment->amount);
    }

    public function test_payment_pending_state(): void
    {
        $payment = Payment::factory()->pending()->create();

        $this->assertEquals('pending', $payment->status);
        $this->assertNull($payment->verified_at);
    }

    // =========================================================
    // Setting CRUD
    // =========================================================

    public function test_can_create_and_retrieve_setting(): void
    {
        Setting::create([
            'key'   => 'site_name',
            'value' => 'My Dress Rental',
            'type'  => 'text',
            'group' => 'site',
            'label' => 'Site Name',
        ]);

        $this->assertDatabaseHas('settings', ['key' => 'site_name']);
        $this->assertEquals('My Dress Rental', Setting::get('site_name'));
    }

    public function test_setting_returns_default_when_key_missing(): void
    {
        $value = Setting::get('non_existent_key', 'default_value');

        $this->assertEquals('default_value', $value);
    }

    public function test_setting_can_be_updated(): void
    {
        Setting::create([
            'key'   => 'advance_payment_percentage',
            'value' => '30',
            'type'  => 'integer',
            'group' => 'booking',
            'label' => 'Advance Payment %',
        ]);

        Setting::set('advance_payment_percentage', '50');

        $this->assertEquals(50, Setting::get('advance_payment_percentage'));
    }

    // =========================================================
    // Page CRUD
    // =========================================================

    public function test_can_create_and_read_page(): void
    {
        $page = Page::factory()->create(['title' => 'Privacy Policy', 'status' => 'active']);

        $this->assertDatabaseHas('pages', ['title' => 'Privacy Policy', 'status' => 'active']);
        $this->assertNotEmpty($page->slug);
    }

    public function test_page_slug_is_auto_generated_from_title(): void
    {
        $page = Page::factory()->create(['title' => 'About Our Company']);

        $this->assertStringContainsString('about-our-company', $page->slug);
    }

    public function test_page_active_scope(): void
    {
        Page::factory()->count(2)->create(['status' => 'active']);
        Page::factory()->inactive()->create();

        $this->assertCount(2, Page::active()->get());
    }

    // =========================================================
    // Relationship Tests
    // =========================================================

    public function test_dress_belongs_to_category(): void
    {
        $category = DressCategory::factory()->create();
        $dress    = Dress::factory()->create(['category_id' => $category->id]);

        $this->assertEquals($category->id, $dress->category->id);
        $this->assertEquals($category->name, $dress->category->name);
    }

    public function test_category_has_many_dresses(): void
    {
        $category = DressCategory::factory()->create();
        Dress::factory()->count(3)->create(['category_id' => $category->id]);

        $this->assertCount(3, $category->dresses);
    }

    public function test_dress_has_many_images_with_primary(): void
    {
        $dress = Dress::factory()->create();
        DressImage::factory()->primary()->create(['dress_id' => $dress->id]);
        DressImage::factory()->count(2)->create(['dress_id' => $dress->id]);

        $dress->refresh();
        $this->assertCount(3, $dress->images);
        $this->assertNotNull($dress->primaryImage());
        $this->assertTrue($dress->primaryImage()->is_primary);
    }

    public function test_booking_belongs_to_user_and_dress(): void
    {
        $user    = User::factory()->create();
        $dress   = Dress::factory()->create();
        $booking = Booking::factory()->create([
            'user_id'  => $user->id,
            'dress_id' => $dress->id,
        ]);

        $this->assertEquals($user->id, $booking->user->id);
        $this->assertEquals($dress->id, $booking->dress->id);
    }

    public function test_user_has_many_bookings(): void
    {
        $user = User::factory()->create();
        Booking::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->bookings);
    }

    public function test_booking_has_many_payments(): void
    {
        $user    = User::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $user->id]);
        Payment::factory()->count(2)->create([
            'booking_id' => $booking->id,
            'user_id'    => $user->id,
        ]);

        $this->assertCount(2, $booking->payments);
    }

    public function test_booking_total_paid_sums_completed_payments(): void
    {
        $user    = User::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $user->id]);

        Payment::factory()->create([
            'booking_id' => $booking->id,
            'user_id'    => $user->id,
            'amount'     => 500.00,
            'status'     => 'completed',
        ]);
        Payment::factory()->create([
            'booking_id' => $booking->id,
            'user_id'    => $user->id,
            'amount'     => 300.00,
            'status'     => 'completed',
        ]);
        // A failed payment that should NOT be counted
        Payment::factory()->failed()->create([
            'booking_id' => $booking->id,
            'user_id'    => $user->id,
            'amount'     => 999.00,
        ]);

        $this->assertEquals(800.00, $booking->total_paid);
    }

    public function test_dress_and_ornament_many_to_many_relationship(): void
    {
        $dress     = Dress::factory()->create();
        $ornaments = Ornament::factory()->count(3)->create();
        $dress->ornaments()->attach($ornaments->pluck('id'));

        $dress->refresh();
        $this->assertCount(3, $dress->ornaments);

        foreach ($ornaments as $ornament) {
            $this->assertContains($dress->id, $ornament->dresses->pluck('id')->toArray());
        }
    }

    public function test_deleting_user_cascades_to_bookings(): void
    {
        $user      = User::factory()->create();
        $booking   = Booking::factory()->create(['user_id' => $user->id]);
        $bookingId = $booking->id;

        $user->delete();

        $this->assertDatabaseMissing('bookings', ['id' => $bookingId]);
    }

    public function test_deleting_dress_cascades_to_bookings_and_images(): void
    {
        $dress     = Dress::factory()->create();
        DressImage::factory()->count(2)->create(['dress_id' => $dress->id]);
        $booking   = Booking::factory()->create(['dress_id' => $dress->id]);
        $dressId   = $dress->id;
        $bookingId = $booking->id;

        $dress->delete();

        $this->assertDatabaseMissing('dresses',      ['id' => $dressId]);
        $this->assertDatabaseMissing('bookings',     ['id' => $bookingId]);
        $this->assertDatabaseMissing('dress_images', ['dress_id' => $dressId]);
    }

    // =========================================================
    // TestSeeder Integration
    // =========================================================

    public function test_test_seeder_creates_expected_records(): void
    {
        $this->seed(TestSeeder::class);

        // Users
        $this->assertDatabaseHas('users', ['email' => 'admin@test.com',    'role' => 'admin']);
        $this->assertDatabaseHas('users', ['email' => 'customer@test.com', 'role' => 'user']);

        // Categories (parent + child hierarchy)
        $this->assertDatabaseHas('dress_categories', ['name' => 'Wedding Collection']);
        $this->assertDatabaseHas('dress_categories', ['name' => 'Bridal Wear']);

        // Dresses
        $this->assertDatabaseHas('dresses', ['name' => 'Red Bridal Dress', 'is_featured' => true]);
        $this->assertDatabaseHas('dresses', ['name' => 'Blue Party Dress']);
        $this->assertDatabaseHas('dresses', ['name' => 'Unavailable Gown', 'status' => 'unavailable']);

        // Ornaments
        $this->assertDatabaseHas('ornaments', ['name' => 'Golden Necklace', 'category' => 'jewelry']);
        $this->assertDatabaseHas('ornaments', ['name' => 'Hair Pin Set',    'category' => 'hair_accessories']);
        $this->assertDatabaseHas('ornaments', ['name' => 'Vintage Handbag', 'status'   => 'unavailable']);

        // Pages
        $this->assertDatabaseHas('pages', ['title' => 'Privacy Policy',      'status' => 'active']);
        $this->assertDatabaseHas('pages', ['title' => 'Terms and Conditions', 'status' => 'active']);
        $this->assertDatabaseHas('pages', ['title' => 'Draft Page',           'status' => 'inactive']);

        // Settings
        $this->assertDatabaseHas('settings', ['key' => 'site_name',                 'value' => 'Test Dress Rental']);
        $this->assertDatabaseHas('settings', ['key' => 'advance_payment_percentage', 'value' => '30']);
        $this->assertDatabaseHas('settings', ['key' => 'esewa_enabled',              'value' => '1']);
    }

    public function test_test_seeder_creates_linked_bookings_and_payments(): void
    {
        $this->seed(TestSeeder::class);

        $customer = User::where('email', 'customer@test.com')->first();

        $this->assertGreaterThanOrEqual(2, $customer->bookings()->count());
        $this->assertGreaterThanOrEqual(1, $customer->payments()->count());
    }

    public function test_test_seeder_creates_dress_ornament_associations(): void
    {
        $this->seed(TestSeeder::class);

        $featuredDress = Dress::where('name', 'Red Bridal Dress')->first();

        $this->assertGreaterThanOrEqual(2, $featuredDress->ornaments()->count());
    }

    public function test_test_seeder_creates_category_hierarchy(): void
    {
        $this->seed(TestSeeder::class);

        $parent = DressCategory::where('name', 'Wedding Collection')->first();
        $child  = DressCategory::where('name', 'Bridal Wear')->first();

        $this->assertNotNull($parent);
        $this->assertNotNull($child);
        $this->assertEquals($parent->id, $child->parent_id);
    }
}
