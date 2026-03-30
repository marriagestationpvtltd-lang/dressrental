<?php

namespace App\Http\Controllers;

use App\Models\Dress;
use App\Models\DressCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(DressCategory $category, Request $request)
    {
        if (! $category->is_active) {
            abort(404);
        }

        // Collect all category IDs to query: this category + its active subcategories
        $categoryIds = collect([$category->id]);

        if ($category->isTopLevel()) {
            $subcategories = $category->activeSubcategories()
                ->withCount(['dresses' => fn ($q) => $q->available()])
                ->get();

            if ($request->filled('subcategory')) {
                // Narrow to only the requested subcategory
                $filtered    = $subcategories->firstWhere('slug', $request->subcategory);
                $categoryIds = $filtered ? collect([$filtered->id]) : collect([]);
            } else {
                $categoryIds = $categoryIds->merge($subcategories->pluck('id'));
            }
        } else {
            $subcategories = collect();
        }

        $query = Dress::with(['images', 'category'])
            ->available()
            ->whereIn('category_id', $categoryIds);

        if ($request->filled('size')) {
            $query->where('size', $request->size);
        }

        if ($request->filled('min_price')) {
            $query->where('price_per_day', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_day', '<=', $request->max_price);
        }

        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price_per_day'),
            'price_desc' => $query->orderByDesc('price_per_day'),
            'popular'    => $query->orderByDesc('views'),
            default      => $query->latest(),
        };

        $dresses = $query->paginate(12)->withQueryString();
        $sizes   = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Free Size'];

        return view('categories.show', compact('category', 'dresses', 'sizes', 'subcategories'));
    }
}
