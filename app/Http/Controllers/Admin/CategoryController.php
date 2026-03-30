<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DressCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = DressCategory::withCount('dresses')
            ->with(['subcategories' => fn ($q) => $q->withCount('dresses')])
            ->topLevel()
            ->orderBy('sort_order')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = DressCategory::topLevel()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:dress_categories',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:100',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'boolean',
            'parent_id'   => ['nullable', Rule::exists('dress_categories', 'id')->whereNull('parent_id')],
        ]);

        $data['slug']      = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        DressCategory::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created!');
    }

    public function edit(DressCategory $category)
    {
        // Exclude the current category and all its own subcategories to prevent invalid hierarchy
        $excludeIds = $category->subcategories()->pluck('id')->push($category->id);

        $parentCategories = DressCategory::topLevel()
            ->where('is_active', true)
            ->whereNotIn('id', $excludeIds)
            ->orderBy('sort_order')
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, DressCategory $category)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:dress_categories,name,' . $category->id,
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:100',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'boolean',
            'parent_id'   => ['nullable', Rule::exists('dress_categories', 'id')->whereNull('parent_id')],
        ]);

        $data['slug']      = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated!');
    }

    public function destroy(DressCategory $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted.');
    }
}
