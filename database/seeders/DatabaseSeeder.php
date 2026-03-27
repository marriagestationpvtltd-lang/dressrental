<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Default seeder — used for local development and testing.
 * Runs ProductionSeeder (admin user) then SampleDataSeeder
 * (demo user, categories, sample dresses).
 *
 * For production use ProductionSeeder directly:
 *   php artisan db:seed --class=ProductionSeeder --force
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProductionSeeder::class,
            SampleDataSeeder::class,
        ]);
    }
}
