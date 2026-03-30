<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'google_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('google_id')->nullable()->unique()->after('email');
            });
        }

        $settings = [
            [
                'key'         => 'google_client_id',
                'value'       => '',
                'type'        => 'text',
                'group'       => 'oauth',
                'label'       => 'Google Client ID',
                'description' => 'OAuth 2.0 Client ID from Google Cloud Console. Required for "Login with Google".',
            ],
            [
                'key'         => 'google_client_secret',
                'value'       => '',
                'type'        => 'password',
                'group'       => 'oauth',
                'label'       => 'Google Client Secret',
                'description' => 'OAuth 2.0 Client Secret from Google Cloud Console. Required for "Login with Google".',
            ],
        ];

        foreach ($settings as $data) {
            Setting::firstOrCreate(['key' => $data['key']], $data);
        }

        Setting::clearCache();
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('google_id');
        });

        Setting::whereIn('key', ['google_client_id', 'google_client_secret'])->delete();
        Setting::clearCache();
    }
};
