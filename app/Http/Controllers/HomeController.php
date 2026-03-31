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
            ->take(12)
            ->get();

        $categories = DressCategory::withCount(['dresses' => fn ($q) => $q->available()])
            ->with(['activeSubcategories' => fn ($q) => $q->withCount(['dresses' => fn ($q2) => $q2->available()])])
            ->where('is_active', true)
            ->topLevel()
            ->orderBy('sort_order')
            ->get();

        $newArrivals = Dress::with(['images', 'category'])
            ->available()
            ->latest()
            ->take(4)
            ->get();

        // Load top categories with a preview of their latest available dresses
        $showcaseCategories = DressCategory::where('is_active', true)
            ->topLevel()
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        foreach ($showcaseCategories as $cat) {
            $previewDresses = Dress::with('images')
                ->available()
                ->where('category_id', $cat->id)
                ->latest()
                ->take(4)
                ->get();

            // Attach the parent category to each dress to avoid extra queries in the card component
            foreach ($previewDresses as $dress) {
                $dress->setRelation('category', $cat);
            }

            $cat->setRelation('previewDresses', $previewDresses);
        }

        $showcaseCategories = $showcaseCategories->filter(fn ($cat) => $cat->previewDresses->count() > 0)->values();

        return view('home', compact('featuredDresses', 'categories', 'newArrivals', 'showcaseCategories'));
    }
}
