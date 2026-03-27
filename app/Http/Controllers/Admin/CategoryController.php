<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DressCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = DressCategory::withCount('dresses')->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255|unique:dress_categories',
            'description'=> 'nullable|string',
            'icon'       => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'boolean',
        ]);

        $data['slug']      = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        DressCategory::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created!');
    }

    public function edit(DressCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, DressCategory $category)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255|unique:dress_categories,name,' . $category->id,
            'description'=> 'nullable|string',
            'icon'       => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'boolean',
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
