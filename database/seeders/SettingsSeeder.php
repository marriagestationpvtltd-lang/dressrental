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

            // ─── Site (additional) ───────────────────────────────────────────
            [
                'key'         => 'meta_description',
                'value'       => 'Rent premium dresses in Nepal. Easy booking with Nepali calendar, pay via eSewa & Khalti.',
                'type'        => 'textarea',
                'group'       => 'site',
                'label'       => 'Meta Description (SEO)',
                'description' => 'Short description shown in search engine results (max 160 characters).',
            ],
            [
                'key'         => 'social_facebook',
                'value'       => '',
                'type'        => 'text',
                'group'       => 'site',
                'label'       => 'Facebook Page URL',
                'description' => 'Full URL to your Facebook page (e.g. https://facebook.com/yourpage).',
            ],
            [
                'key'         => 'social_instagram',
                'value'       => '',
                'type'        => 'text',
                'group'       => 'site',
                'label'       => 'Instagram Profile URL',
                'description' => 'Full URL to your Instagram profile (e.g. https://instagram.com/yourhandle).',
            ],
            [
                'key'         => 'social_whatsapp',
                'value'       => '',
                'type'        => 'text',
                'group'       => 'site',
                'label'       => 'WhatsApp Number',
                'description' => 'WhatsApp contact number with country code (e.g. 9779800000000).',
            ],

            // ─── Booking (additional) ────────────────────────────────────────
            [
                'key'         => 'late_return_fine_per_day',
                'value'       => '0',
                'type'        => 'decimal',
                'group'       => 'booking',
                'label'       => 'Late Return Fine per Day (रू)',
                'description' => 'Fine charged per extra day when a dress is returned late.',
            ],

            // ─── Payment (gateway credentials) ───────────────────────────────
            [
                'key'         => 'esewa_merchant_id',
                'value'       => '',
                'type'        => 'text',
                'group'       => 'payment',
                'label'       => 'eSewa Merchant ID',
                'description' => 'Your eSewa merchant product code. Overrides the ESEWA_MERCHANT_ID env variable when set.',
            ],
            [
                'key'         => 'esewa_secret_key',
                'value'       => '',
                'type'        => 'password',
                'group'       => 'payment',
                'label'       => 'eSewa Secret Key',
                'description' => 'Your eSewa HMAC secret key. Overrides the ESEWA_SECRET_KEY env variable when set.',
            ],
            [
                'key'         => 'esewa_sandbox',
                'value'       => '',
                'type'        => 'boolean',
                'group'       => 'payment',
                'label'       => 'eSewa Sandbox Mode',
                'description' => 'Enable to use the eSewa test/sandbox environment. Overrides the ESEWA_SANDBOX env variable when checked.',
            ],
            [
                'key'         => 'khalti_public_key',
                'value'       => '',
                'type'        => 'text',
                'group'       => 'payment',
                'label'       => 'Khalti Public Key',
                'description' => 'Your Khalti public key. Overrides the KHALTI_PUBLIC_KEY env variable when set.',
            ],
            [
                'key'         => 'khalti_secret_key',
                'value'       => '',
                'type'        => 'password',
                'group'       => 'payment',
                'label'       => 'Khalti Secret Key',
                'description' => 'Your Khalti secret key. Overrides the KHALTI_SECRET_KEY env variable when set.',
            ],
            [
                'key'         => 'khalti_sandbox',
                'value'       => '',
                'type'        => 'boolean',
                'group'       => 'payment',
                'label'       => 'Khalti Sandbox Mode',
                'description' => 'Enable to use the Khalti test/sandbox environment. Overrides the KHALTI_SANDBOX env variable when checked.',
            ],
        ];

        foreach ($settings as $data) {
            Setting::firstOrCreate(['key' => $data['key']], $data);
        }

        Setting::clearCache();
    }
}
