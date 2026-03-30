<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ─── Site ────────────────────────────────────────────────────────
            [
                'key'         => 'site_name',
                'value'       => 'DressRental Nepal',
                'type'        => 'text',
                'group'       => 'site',
                'label'       => 'Site Name',
                'description' => 'The name of the website shown in the browser title and emails.',
            ],
            [
                'key'         => 'site_tagline',
                'value'       => 'Premium Dress Rental Service',
                'type'        => 'text',
                'group'       => 'site',
                'label'       => 'Site Tagline',
                'description' => 'A short description displayed on the homepage.',
            ],
            [
                'key'         => 'contact_phone',
                'value'       => '',
                'type'        => 'text',
                'group'       => 'site',
                'label'       => 'Contact Phone',
                'description' => 'Business contact phone number.',
            ],
            [
                'key'         => 'contact_email',
                'value'       => '',
                'type'        => 'text',
                'group'       => 'site',
                'label'       => 'Contact Email',
                'description' => 'Business contact email address.',
            ],
            [
                'key'         => 'contact_address',
                'value'       => '',
                'type'        => 'textarea',
                'group'       => 'site',
                'label'       => 'Contact Address',
                'description' => 'Physical business address.',
            ],
            [
                'key'         => 'currency',
                'value'       => 'NPR',
                'type'        => 'text',
                'group'       => 'site',
                'label'       => 'Currency Code',
                'description' => 'ISO currency code (e.g. NPR, USD).',
            ],
            [
                'key'         => 'currency_symbol',
                'value'       => 'रू',
                'type'        => 'text',
                'group'       => 'site',
                'label'       => 'Currency Symbol',
                'description' => 'Symbol displayed before amounts (e.g. रू, $).',
            ],

            // ─── Booking ─────────────────────────────────────────────────────
            [
                'key'         => 'advance_payment_percentage',
                'value'       => '50',
                'type'        => 'integer',
                'group'       => 'booking',
                'label'       => 'Advance Payment Percentage',
                'description' => 'Percentage of total amount collected as advance at booking (e.g. 50 for 50%).',
            ],
            [
                'key'         => 'min_rental_days',
                'value'       => '1',
                'type'        => 'integer',
                'group'       => 'booking',
                'label'       => 'Minimum Rental Days',
                'description' => 'Minimum number of days a dress can be rented.',
            ],
            [
                'key'         => 'max_rental_days',
                'value'       => '365',
                'type'        => 'integer',
                'group'       => 'booking',
                'label'       => 'Maximum Rental Days',
                'description' => 'Maximum number of days a dress can be rented.',
            ],
            [
                'key'         => 'cancellation_notice_hours',
                'value'       => '24',
                'type'        => 'integer',
                'group'       => 'booking',
                'label'       => 'Cancellation Notice (Hours)',
                'description' => 'Minimum hours before booking start that cancellation is allowed.',
            ],

            // ─── Payment ─────────────────────────────────────────────────────
            [
                'key'         => 'esewa_enabled',
                'value'       => '1',
                'type'        => 'boolean',
                'group'       => 'payment',
                'label'       => 'Enable eSewa',
                'description' => 'Allow customers to pay via eSewa.',
            ],
            [
                'key'         => 'khalti_enabled',
                'value'       => '1',
                'type'        => 'boolean',
                'group'       => 'payment',
                'label'       => 'Enable Khalti',
                'description' => 'Allow customers to pay via Khalti.',
            ],
            [
                'key'         => 'esewa_service_charge',
                'value'       => '0',
                'type'        => 'decimal',
                'group'       => 'payment',
                'label'       => 'eSewa Service Charge (रू)',
                'description' => 'Fixed service charge added to eSewa payments.',
            ],
            [
                'key'         => 'esewa_delivery_charge',
                'value'       => '0',
                'type'        => 'decimal',
                'group'       => 'payment',
                'label'       => 'eSewa Delivery Charge (रू)',
                'description' => 'Fixed delivery charge added to eSewa payments.',
            ],
            [
                'key'         => 'tax_percentage',
                'value'       => '0',
                'type'        => 'decimal',
                'group'       => 'payment',
                'label'       => 'Tax Percentage (%)',
                'description' => 'Tax percentage applied on the payment amount (e.g. 13 for 13% VAT).',
            ],

            // ─── AI ──────────────────────────────────────────────────────────
            [
                'key'         => 'gemini_api_key',
                'value'       => '',
                'type'        => 'password',
                'group'       => 'ai',
                'label'       => 'Google Gemini API Key',
                'description' => 'Free API key for Google Gemini AI. Get yours at https://aistudio.google.com/ — used to auto-generate dress descriptions from photos.',
            ],
        ];

        foreach ($settings as $data) {
            Setting::firstOrCreate(['key' => $data['key']], $data);
        }

        Setting::clearCache();
    }
}
