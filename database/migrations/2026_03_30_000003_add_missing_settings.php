<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            // ─── Site ─────────────────────────────────────────────────────────
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

            // ─── Booking ──────────────────────────────────────────────────────
            [
                'key'         => 'late_return_fine_per_day',
                'value'       => '0',
                'type'        => 'decimal',
                'group'       => 'booking',
                'label'       => 'Late Return Fine per Day (रू)',
                'description' => 'Fine charged per extra day when a dress is returned late.',
            ],

            // ─── Payment ──────────────────────────────────────────────────────
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

    public function down(): void
    {
        $keys = [
            'meta_description', 'social_facebook', 'social_instagram', 'social_whatsapp',
            'late_return_fine_per_day',
            'esewa_merchant_id', 'esewa_secret_key', 'esewa_sandbox',
            'khalti_public_key', 'khalti_secret_key', 'khalti_sandbox',
        ];

        Setting::whereIn('key', $keys)->delete();
        Setting::clearCache();
    }
};
