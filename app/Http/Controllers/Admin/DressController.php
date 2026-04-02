<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dress;
use App\Models\DressCategory;
use App\Models\DressImage;
use App\Models\Ornament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DressController extends Controller
{
    public function index(Request $request)
    {
        $query = Dress::with(['category', 'images', 'availableSizes'])->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $dresses    = $query->paginate(15)->withQueryString();
        $categories = DressCategory::all();

        return view('admin.dresses.index', compact('dresses', 'categories'));
    }

    public function create()
    {
        $categories = DressCategory::topLevel()
            ->where('is_active', true)
            ->with('activeSubcategories')
            ->orderBy('sort_order')
            ->get();
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Free Size'];
        return view('admin.dresses.create', compact('categories', 'sizes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|exists:dress_categories,id',
            'sizes'          => 'required|array|min:1',
            'sizes.*'        => 'in:XS,S,M,L,XL,XXL,Free Size',
            'price_per_day'  => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'description'    => 'nullable|string',
            'color'          => 'nullable|string|max:100',
            'brand'          => 'nullable|string|max:100',
            'status'         => 'required|in:available,unavailable',
            'is_featured'    => 'boolean',
            'images'         => 'nullable|array',
            'images.*'       => 'image|mimes:jpg,jpeg,png,webp|max:3072',
            'pricings'       => 'nullable|array',
            'pricings.*.days'  => 'required_with:pricings|integer|min:1',
            'pricings.*.price' => 'required_with:pricings|numeric|min:0',
        ]);

        $data['slug']        = Str::slug($data['name']) . '-' . Str::random(5);
        $data['is_featured'] = $request->boolean('is_featured');

        $dress = Dress::create($data);

        // Save available sizes
        $this->syncSizes($dress, $data['sizes']);

        // Save pricing tiers
        $this->syncPricings($dress, $data['pricings'] ?? []);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $image) {
                $path = $image->store('dresses', 'public');
                DressImage::create([
                    'dress_id'   => $dress->id,
                    'image_path' => $path,
                    'is_primary' => ($i === 0),
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()->route('admin.dresses.index')
            ->with('success', 'Dress created successfully!');
    }

    public function edit(Dress $dress)
    {
        $dress->load(['images', 'ornaments', 'availableSizes', 'pricings']);
        $categories = DressCategory::topLevel()
            ->where('is_active', true)
            ->with('activeSubcategories')
            ->orderBy('sort_order')
            ->get();
        $sizes     = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Free Size'];
        $ornaments = Ornament::orderBy('name')->get();
        return view('admin.dresses.edit', compact('dress', 'categories', 'sizes', 'ornaments'));
    }

    public function update(Request $request, Dress $dress)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|exists:dress_categories,id',
            'sizes'          => 'required|array|min:1',
            'sizes.*'        => 'in:XS,S,M,L,XL,XXL,Free Size',
            'price_per_day'  => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'description'    => 'nullable|string',
            'color'          => 'nullable|string|max:100',
            'brand'          => 'nullable|string|max:100',
            'status'         => 'required|in:available,unavailable',
            'is_featured'    => 'boolean',
            'images'         => 'nullable|array',
            'images.*'       => 'image|mimes:jpg,jpeg,png,webp|max:3072',
            'ornament_ids'   => 'nullable|array',
            'ornament_ids.*' => 'exists:ornaments,id',
            'pricings'       => 'nullable|array',
            'pricings.*.days'  => 'required_with:pricings|integer|min:1',
            'pricings.*.price' => 'required_with:pricings|numeric|min:0',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $dress->update($data);

        // Sync available sizes
        $dress->availableSizes()->delete();
        $this->syncSizes($dress, $data['sizes']);

        // Sync pricing tiers
        $dress->pricings()->delete();
        $this->syncPricings($dress, $data['pricings'] ?? []);

        $dress->ornaments()->sync($request->input('ornament_ids', []));

        if ($request->hasFile('images')) {
            $startOrder = $dress->images()->max('sort_order') + 1;
            foreach ($request->file('images') as $i => $image) {
                $path = $image->store('dresses', 'public');
                DressImage::create([
                    'dress_id'   => $dress->id,
                    'image_path' => $path,
                    'is_primary' => ($dress->images()->count() === 0 && $i === 0),
                    'sort_order' => $startOrder + $i,
                ]);
            }
        }

        return redirect()->route('admin.dresses.index')
            ->with('success', 'Dress updated successfully!');
    }

    public function destroy(Dress $dress)
    {
        foreach ($dress->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }
        $dress->delete();
        return redirect()->route('admin.dresses.index')
            ->with('success', 'Dress deleted.');
    }

    public function deleteImage(DressImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return back()->with('success', 'Image removed.');
    }

    private function syncSizes(Dress $dress, array $sizes): void
    {
        $uniqueSizes = array_unique(array_filter($sizes));
        if (empty($uniqueSizes)) {
            return;
        }
        $rows = array_map(
            fn ($s) => ['dress_id' => $dress->id, 'size' => $s, 'created_at' => now(), 'updated_at' => now()],
            $uniqueSizes
        );
        \App\Models\DressSize::insert(array_values($rows));
    }

    private function syncPricings(Dress $dress, array $pricings): void
    {
        $rows = [];
        foreach ($pricings as $p) {
            $days = (int) $p['days'];
            if ($days > 0) {
                $rows[$days] = ['dress_id' => $dress->id, 'days' => $days, 'price' => $p['price'], 'created_at' => now(), 'updated_at' => now()];
            }
        }
        if ($rows) {
            \App\Models\DressPricing::insert(array_values($rows));
        }
    }
}
