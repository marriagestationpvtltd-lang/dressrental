<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ornament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrnamentController extends Controller
{
    public function index(Request $request)
    {
        $query = Ornament::latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $ornaments  = $query->paginate(15)->withQueryString();
        $categories = ['jewelry', 'hair_accessories', 'footwear', 'handbag', 'other'];

        return view('admin.ornaments.index', compact('ornaments', 'categories'));
    }

    public function create()
    {
        $categories = ['jewelry', 'hair_accessories', 'footwear', 'handbag', 'other'];
        return view('admin.ornaments.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'category'       => 'required|in:jewelry,hair_accessories,footwear,handbag,other',
            'price_per_day'  => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'description'    => 'nullable|string',
            'status'         => 'required|in:available,unavailable',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('ornaments', 'public');
        }

        Ornament::create($data);

        return redirect()->route('admin.ornaments.index')
            ->with('success', 'Ornament created successfully!');
    }

    public function edit(Ornament $ornament)
    {
        $categories = ['jewelry', 'hair_accessories', 'footwear', 'handbag', 'other'];
        return view('admin.ornaments.edit', compact('ornament', 'categories'));
    }

    public function update(Request $request, Ornament $ornament)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'category'       => 'required|in:jewelry,hair_accessories,footwear,handbag,other',
            'price_per_day'  => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'description'    => 'nullable|string',
            'status'         => 'required|in:available,unavailable',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        if ($request->hasFile('image')) {
            if ($ornament->image_path) {
                Storage::disk('public')->delete($ornament->image_path);
            }
            $data['image_path'] = $request->file('image')->store('ornaments', 'public');
        }

        $ornament->update($data);

        return redirect()->route('admin.ornaments.index')
            ->with('success', 'Ornament updated successfully!');
    }

    public function destroy(Ornament $ornament)
    {
        if ($ornament->image_path) {
            Storage::disk('public')->delete($ornament->image_path);
        }
        $ornament->delete();

        return redirect()->route('admin.ornaments.index')
            ->with('success', 'Ornament deleted.');
    }
}
