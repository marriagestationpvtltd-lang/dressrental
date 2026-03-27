<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Production seeder — runs on live server.
 * Creates only the essential admin account.
 * No sample dresses, no demo users.
 *
 * Usage:
 *   php artisan db:seed --class=ProductionSeeder --force
 */
class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $email    = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $email || ! $password) {
            $this->command->error(
                'ProductionSeeder requires ADMIN_EMAIL and ADMIN_PASSWORD to be set in .env.'
            );
            return;
        }

        // Create admin account only if it does not already exist.
        User::firstOrCreate(
            ['email' => $email],
            [
                'name'     => 'Admin',
                'password' => Hash::make($password),
                'role'     => 'admin',
                'phone'    => env('ADMIN_PHONE', ''),
            ]
        );
    }
}
