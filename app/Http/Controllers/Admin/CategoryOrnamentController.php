<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DressCategory;
use App\Models\Ornament;
use Illuminate\Http\Request;

class CategoryOrnamentController extends Controller
{
    /**
     * Show the manage-recommendations page for a category.
     */
    public function manage(DressCategory $category)
    {
        $recommended = $category->recommendedOrnaments()->get();

        $search    = request('search');
        $available = Ornament::query()
            ->whereNotIn('id', $recommended->pluck('id'))
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->get();

        return view('admin.categories.ornament-recommendations', compact('category', 'recommended', 'available', 'search'));
    }

    /**
     * Attach one or more ornaments as recommendations for a category.
     */
    public function store(Request $request, DressCategory $category)
    {
        $request->validate([
            'ornament_ids'   => 'required|array|min:1',
            'ornament_ids.*' => 'exists:ornaments,id',
        ]);

        // Determine starting sort_order after existing entries
        $maxOrder = $category->recommendedOrnaments()->max('sort_order') ?? -1;

        $syncData = collect($request->ornament_ids)
            ->mapWithKeys(fn ($id, $i) => [(int) $id => ['sort_order' => $maxOrder + $i + 1]]);

        $category->recommendedOrnaments()->syncWithoutDetaching($syncData);

        $count = count($request->ornament_ids);

        return redirect()->route('admin.categories.ornaments.manage', $category)
            ->with('success', $count . ' ornament' . ($count !== 1 ? 's' : '') . ' added to recommendations.');
    }

    /**
     * Remove a single ornament from a category's recommendations.
     */
    public function destroy(DressCategory $category, Ornament $ornament)
    {
        $category->recommendedOrnaments()->detach($ornament->id);

        return redirect()->route('admin.categories.ornaments.manage', $category)
            ->with('success', '"' . $ornament->name . '" removed from recommendations.');
    }

    /**
     * Update the sort order of recommendations via drag-and-drop / AJAX.
     */
    public function reorder(Request $request, DressCategory $category)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'exists:ornaments,id',
        ]);

        foreach ($request->order as $position => $ornamentId) {
            $category->recommendedOrnaments()->updateExistingPivot($ornamentId, ['sort_order' => $position]);
        }

        return response()->json(['success' => true]);
    }
}
