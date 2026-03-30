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

        $query = Dress::with(['images', 'category'])
            ->available()
            ->where('category_id', $category->id);

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

        return view('categories.show', compact('category', 'dresses', 'sizes'));
    }
}
