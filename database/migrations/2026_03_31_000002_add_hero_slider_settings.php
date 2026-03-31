<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            [
                'key'         => 'hero_slider_animation',
                'value'       => 'slide',
                'type'        => 'text',
                'group'       => 'site',
                'label'       => 'Hero Slider Animation',
                'description' => 'Animation type for hero banner slider: slide, fade, or zoom.',
            ],
            [
                'key'         => 'hero_slider_interval',
                'value'       => '5000',
                'type'        => 'integer',
                'group'       => 'site',
                'label'       => 'Hero Slider Interval (ms)',
                'description' => 'Time in milliseconds each hero slide is displayed (default: 5000).',
            ],
            [
                'key'         => 'footer_slider_animation',
                'value'       => 'slide',
                'type'        => 'text',
                'group'       => 'site',
                'label'       => 'Footer Dress Slider Animation',
                'description' => 'Animation type for footer dress slider: slide or fade.',
            ],
            [
                'key'         => 'footer_slider_interval',
                'value'       => '3000',
                'type'        => 'integer',
                'group'       => 'site',
                'label'       => 'Footer Dress Slider Interval (ms)',
                'description' => 'Time in milliseconds each footer dress slide is displayed (default: 3000).',
            ],
        ];

        foreach ($settings as $data) {
            Setting::firstOrCreate(['key' => $data['key']], $data);
        }

        Setting::clearCache();
    }

    public function down(): void
    {
        Setting::whereIn('key', [
            'hero_slider_animation',
            'hero_slider_interval',
            'footer_slider_animation',
            'footer_slider_interval',
        ])->delete();

        Setting::clearCache();
    }
};
