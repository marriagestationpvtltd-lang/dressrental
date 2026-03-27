<?php

namespace Database\Seeders;

use App\Models\Dress;
use App\Models\DressCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Sample-data seeder — for local development and testing only.
 * Adds a demo customer account, all six dress categories,
 * and twelve sample dresses so the UI looks populated.
 *
 * Usage (testing database):
 *   php artisan db:seed --class=SampleDataSeeder --force
 *
 * Never run this on the production database.
 */
class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Demo customer account
        User::firstOrCreate(['email' => 'user@dressrental.com'], [
            'name'     => 'Demo User',
            'password' => Hash::make('password'),
            'role'     => 'user',
            'phone'    => '9800000002',
        ]);

        // Dress categories
        $categories = [
            ['name' => 'Bridal Wear',    'icon' => '👰', 'sort_order' => 1],
            ['name' => 'Party Dresses',  'icon' => '🎉', 'sort_order' => 2],
            ['name' => 'Ethnic Wear',    'icon' => '🪷', 'sort_order' => 3],
            ['name' => 'Casual Dresses', 'icon' => '👗', 'sort_order' => 4],
            ['name' => 'Formal Wear',    'icon' => '💼', 'sort_order' => 5],
            ['name' => 'Festival Wear',  'icon' => '🎊', 'sort_order' => 6],
        ];

        foreach ($categories as $catData) {
            DressCategory::firstOrCreate(['name' => $catData['name']], [
                'slug'       => Str::slug($catData['name']),
                'icon'       => $catData['icon'],
                'is_active'  => true,
                'sort_order' => $catData['sort_order'],
            ]);
        }

        // Sample dresses
        $dresses = [
            ['name' => 'Red Silk Bridal Lehenga',  'category' => 'Bridal Wear',   'size' => 'M',         'price' => 1500, 'deposit' => 3000, 'featured' => true,  'color' => 'Red'],
            ['name' => 'Golden Anarkali Suit',      'category' => 'Ethnic Wear',   'size' => 'L',         'price' => 800,  'deposit' => 1500, 'featured' => true,  'color' => 'Gold'],
            ['name' => 'Blue Georgette Saree',      'category' => 'Ethnic Wear',   'size' => 'Free Size', 'price' => 600,  'deposit' => 1000, 'featured' => false, 'color' => 'Blue'],
            ['name' => 'Black Evening Gown',        'category' => 'Party Dresses', 'size' => 'S',         'price' => 1200, 'deposit' => 2000, 'featured' => true,  'color' => 'Black'],
            ['name' => 'Pink Floral Party Dress',   'category' => 'Party Dresses', 'size' => 'XS',        'price' => 700,  'deposit' => 1000, 'featured' => false, 'color' => 'Pink'],
            ['name' => 'Purple Cocktail Dress',     'category' => 'Party Dresses', 'size' => 'M',         'price' => 900,  'deposit' => 1500, 'featured' => true,  'color' => 'Purple'],
            ['name' => 'Cream Chiffon Saree',       'category' => 'Ethnic Wear',   'size' => 'Free Size', 'price' => 500,  'deposit' => 800,  'featured' => false, 'color' => 'Cream'],
            ['name' => 'Royal Blue Sherwani',       'category' => 'Formal Wear',   'size' => 'XL',        'price' => 1100, 'deposit' => 2000, 'featured' => false, 'color' => 'Blue'],
            ['name' => 'Green Banarasi Lehenga',    'category' => 'Bridal Wear',   'size' => 'L',         'price' => 2000, 'deposit' => 4000, 'featured' => true,  'color' => 'Green'],
            ['name' => 'White Lace Wedding Dress',  'category' => 'Bridal Wear',   'size' => 'S',         'price' => 2500, 'deposit' => 5000, 'featured' => true,  'color' => 'White'],
            ['name' => 'Orange Dashain Kurta',      'category' => 'Festival Wear', 'size' => 'M',         'price' => 400,  'deposit' => 600,  'featured' => false, 'color' => 'Orange'],
            ['name' => 'Yellow Tihar Dress',        'category' => 'Festival Wear', 'size' => 'L',         'price' => 350,  'deposit' => 500,  'featured' => false, 'color' => 'Yellow'],
        ];

        foreach ($dresses as $d) {
            $cat = DressCategory::where('name', $d['category'])->first();
            if (! $cat) {
                continue;
            }

            Dress::firstOrCreate(['name' => $d['name']], [
                'category_id'    => $cat->id,
                'slug'           => Str::slug($d['name']) . '-' . Str::random(5),
                'size'           => $d['size'],
                'price_per_day'  => $d['price'],
                'deposit_amount' => $d['deposit'],
                'status'         => 'available',
                'is_featured'    => $d['featured'],
                'color'          => $d['color'],
                'description'    => "Premium quality {$d['name']} available for rent. Perfect for special occasions.",
            ]);
        }
    }
}
