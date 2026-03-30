<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Setting::firstOrCreate(
            ['key' => 'gemini_api_key'],
            [
                'value'       => '',
                'type'        => 'password',
                'group'       => 'ai',
                'label'       => 'Google Gemini API Key',
                'description' => 'Free API key for Google Gemini AI. Get yours at https://aistudio.google.com/ — used to auto-generate dress descriptions from photos.',
            ]
        );

        Setting::clearCache();
    }

    public function down(): void
    {
        Setting::where('key', 'gemini_api_key')->delete();
        Setting::clearCache();
    }
};
