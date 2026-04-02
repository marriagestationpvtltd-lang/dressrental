<?php

namespace App\Http\Controllers;

use App\Models\Dress;
use App\Models\DressCategory;
use Illuminate\Http\Request;

class DressController extends Controller
{
    public function index(Request $request)
    {
        $query = Dress::with(['images', 'category', 'availableSizes'])->available();

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('size')) {
            $query->whereHas('availableSizes', fn ($q) => $q->where('size', $request->size));
        }

        if ($request->filled('min_price')) {
            $query->where('price_per_day', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_day', '<=', $request->max_price);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%"));
        }

        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price_per_day'),
            'price_desc' => $query->orderByDesc('price_per_day'),
            'popular'    => $query->orderByDesc('views'),
            default      => $query->latest(),
        };

        $dresses    = $query->paginate(12)->withQueryString();
        $categories = DressCategory::where('is_active', true)->withCount(['dresses' => fn ($q) => $q->available()])->get();
        $sizes      = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Free Size'];

        return view('dresses.index', compact('dresses', 'categories', 'sizes'));
    }

    public function featured(Request $request)
    {
        $query = Dress::with(['images', 'category', 'availableSizes'])->available()->featured();

        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price_per_day'),
            'price_desc' => $query->orderByDesc('price_per_day'),
            'popular'    => $query->orderByDesc('views'),
            default      => $query->latest(),
        };

        $dresses = $query->paginate(12)->withQueryString();

        return view('dresses.featured', compact('dresses'));
    }

    public function newArrivals(Request $request)
    {
        $query = Dress::with(['images', 'category', 'availableSizes'])->available();

        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price_per_day'),
            'price_desc' => $query->orderByDesc('price_per_day'),
            'popular'    => $query->orderByDesc('views'),
            default      => $query->latest(),
        };

        $dresses = $query->paginate(12)->withQueryString();

        return view('dresses.new-arrivals', compact('dresses'));
    }

    public function show(Dress $dress)
    {
        if ($dress->status === 'unavailable') {
            abort(404);
        }

        $dress->increment('views');
        $dress->load(['images', 'category.parent', 'ornaments', 'availableSizes', 'pricings']);

        // ── Smart ornament recommendation cascade ─────────────────
        // Priority 1: ornaments directly attached to this specific dress
        $directOrnaments = $dress->ornaments()->available()->get();

        // Priority 2: ornaments recommended for the dress's exact category/subcategory
        // Priority 3: ornaments recommended for the parent category (inherited)
        $categoryOrnaments = collect();
        if ($dress->category) {
            $categoryOrnaments = $dress->category->recommendedOrnaments()->available()->get();

            if ($dress->category->parent_id && $dress->category->parent) {
                $parentOrnaments   = $dress->category->parent->recommendedOrnaments()->available()->get();
                $categoryOrnaments = $categoryOrnaments->merge(
                    $parentOrnaments->whereNotIn('id', $categoryOrnaments->pluck('id'))
                );
            }
        }

        // Merge: dress-specific ornaments first, then category-based (no duplicates)
        $ornamentRecommendations = $directOrnaments
            ->merge($categoryOrnaments->whereNotIn('id', $directOrnaments->pluck('id')))
            ->values();

        $dressSizes = $dress->availableSizes->pluck('size')->toArray() ?: ($dress->size ? [$dress->size] : []);
        $recommendations = Dress::with(['images', 'category', 'availableSizes'])
            ->available()
            ->where('id', '!=', $dress->id)
            ->where(fn ($q) => $q->where('category_id', $dress->category_id)
                ->orWhereHas('availableSizes', fn ($s) => $s->whereIn('size', $dressSizes))
                ->orWhereBetween('price_per_day', [
                    $dress->price_per_day * 0.7,
                    $dress->price_per_day * 1.3,
                ])
            )
            ->limit(4)
            ->get();

        $bookedRanges = $dress->activeBookings()->get(['start_date', 'end_date'])
            ->map(fn ($b) => [
                'start' => $b->start_date->format('Y-m-d'),
                'end'   => $b->end_date->format('Y-m-d'),
            ]);

        $dressSizesList  = $dress->availableSizes->pluck('size')->toArray() ?: ($dress->size ? [$dress->size] : []);
        $threeDayPricing = $dress->pricings->firstWhere('days', 3);
        $threeDayPrice   = $threeDayPricing ? (float) $threeDayPricing->price : (float) ($dress->price_per_day * 3);
        $pricingTiersJson = $dress->pricings->pluck('price', 'days')->toArray();

        return view('dresses.show', compact(
            'dress', 'recommendations', 'bookedRanges', 'ornamentRecommendations',
            'dressSizesList', 'threeDayPricing', 'threeDayPrice', 'pricingTiersJson'
        ));
    }
}
