<?php

namespace App\Http\Controllers;

use App\Models\Dress;
use App\Models\DressCategory;

class HomeController extends Controller
{
    public function index()
    {
        $featuredDresses = Dress::with(['images', 'category'])
            ->available()
            ->featured()
            ->latest()
            ->take(8)
            ->get();

        $categories = DressCategory::withCount(['dresses' => fn ($q) => $q->available()])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $newArrivals = Dress::with(['images', 'category'])
            ->available()
            ->latest()
            ->take(4)
            ->get();

        return view('home', compact('featuredDresses', 'categories', 'newArrivals'));
    }
}
