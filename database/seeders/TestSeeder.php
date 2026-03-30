<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Dress;
use App\Models\DressCategory;
use App\Models\DressImage;
use App\Models\Ornament;
use App\Models\Page;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeder for automated testing.
 *
 * Creates a comprehensive dataset covering all tables so that migration
 * tests and feature tests can verify schema, CRUD, and relationships.
 */
class TestSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ────────────────────────────────────────────────
        $admin = User::factory()->create([
            'name'  => 'Test Admin',
            'email' => 'admin@test.com',
            'role'  => 'admin',
        ]);

        $customer = User::factory()->create([
            'name'  => 'Test Customer',
            'email' => 'customer@test.com',
            'role'  => 'user',
        ]);

        // ── Dress Categories ──────────────────────────────────────
        $parentCategory = DressCategory::factory()->create([
            'name'       => 'Wedding Collection',
            'sort_order' => 1,
        ]);

        $bridalCategory = DressCategory::factory()->childOf($parentCategory)->create([
            'name'       => 'Bridal Wear',
            'sort_order' => 1,
        ]);

        $partyCategory = DressCategory::factory()->create([
            'name'       => 'Party Dresses',
            'sort_order' => 2,
        ]);

        // ── Dresses ───────────────────────────────────────────────
        $featuredDress = Dress::factory()->featured()->create([
            'category_id' => $bridalCategory->id,
            'name'        => 'Red Bridal Dress',
            'size'        => 'M',
            'price_per_day'  => 800.00,
            'deposit_amount' => 3000.00,
        ]);

        DressImage::factory()->primary()->create(['dress_id' => $featuredDress->id]);
        DressImage::factory()->count(2)->create(['dress_id' => $featuredDress->id]);

        $partyDress = Dress::factory()->create([
            'category_id' => $partyCategory->id,
            'name'        => 'Blue Party Dress',
            'size'        => 'S',
            'price_per_day'  => 500.00,
            'deposit_amount' => 1500.00,
        ]);

        Dress::factory()->unavailable()->create([
            'category_id' => $partyCategory->id,
            'name'        => 'Unavailable Gown',
        ]);

        // ── Ornaments ─────────────────────────────────────────────
        $necklace   = Ornament::factory()->create(['name' => 'Golden Necklace',   'category' => 'jewelry']);
        $hairPin    = Ornament::factory()->create(['name' => 'Hair Pin Set',       'category' => 'hair_accessories']);
        $handbag    = Ornament::factory()->unavailable()->create(['name' => 'Vintage Handbag', 'category' => 'handbag']);

        $featuredDress->ornaments()->attach([$necklace->id, $hairPin->id]);

        // ── Bookings ──────────────────────────────────────────────
        $pendingBooking = Booking::factory()->create([
            'user_id'  => $customer->id,
            'dress_id' => $featuredDress->id,
        ]);

        $paidBooking = Booking::factory()->paid()->create([
            'user_id'  => $customer->id,
            'dress_id' => $partyDress->id,
        ]);

        Booking::factory()->cancelled()->create([
            'user_id'  => $customer->id,
            'dress_id' => $partyDress->id,
        ]);

        // ── Payments ──────────────────────────────────────────────
        Payment::factory()->create([
            'booking_id'   => $paidBooking->id,
            'user_id'      => $customer->id,
            'payment_type' => 'advance',
        ]);

        Payment::factory()->pending()->create([
            'booking_id'   => $pendingBooking->id,
            'user_id'      => $customer->id,
        ]);

        // ── Settings ──────────────────────────────────────────────
        $settings = [
            ['key' => 'site_name',                 'value' => 'Test Dress Rental', 'type' => 'text',    'group' => 'site',    'label' => 'Site Name'],
            ['key' => 'advance_payment_percentage', 'value' => '30',               'type' => 'integer', 'group' => 'booking', 'label' => 'Advance Payment %'],
            ['key' => 'late_return_fine_per_day',   'value' => '200',              'type' => 'decimal', 'group' => 'booking', 'label' => 'Late Return Fine Per Day'],
            ['key' => 'esewa_enabled',              'value' => '1',                'type' => 'boolean', 'group' => 'payment', 'label' => 'eSewa Enabled'],
            ['key' => 'khalti_enabled',             'value' => '0',                'type' => 'boolean', 'group' => 'payment', 'label' => 'Khalti Enabled'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                array_merge($setting, ['description' => null])
            );
        }

        // ── Pages ─────────────────────────────────────────────────
        Page::factory()->create(['title' => 'Privacy Policy',      'sort_order' => 1]);
        Page::factory()->create(['title' => 'Terms and Conditions', 'sort_order' => 2]);
        Page::factory()->inactive()->create(['title' => 'Draft Page', 'sort_order' => 3]);
    }
}
