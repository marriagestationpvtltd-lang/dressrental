<?php

namespace App\Http\Controllers;

use App\Models\Dress;
use App\Models\DressCategory;
use Illuminate\Http\Request;

class DressController extends Controller
{
    public function index(Request $request)
    {
        $query = Dress::with(['images', 'category'])->available();

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('size')) {
            $query->where('size', $request->size);
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
        $query = Dress::with(['images', 'category'])->available()->featured();

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
        $query = Dress::with(['images', 'category'])->available();

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
        $dress->load(['images', 'category.parent', 'ornaments']);

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

        $recommendations = Dress::with(['images', 'category'])
            ->available()
            ->where('id', '!=', $dress->id)
            ->where(fn ($q) => $q->where('category_id', $dress->category_id)
                ->orWhere('size', $dress->size)
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

        return view('dresses.show', compact('dress', 'recommendations', 'bookedRanges', 'ornamentRecommendations'));
    }
}
