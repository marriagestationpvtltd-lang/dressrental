<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fix parent_id / ID mismatches in dress_categories introduced after
 * sub-categories and ornament categories were added.
 *
 * Two classes of bad data are corrected:
 *  1. Orphaned subcategories – parent_id refers to a category that no
 *     longer exists in the table (e.g. the parent was deleted without
 *     the ON DELETE SET NULL cascade being active at that moment).
 *     Fix: set parent_id = NULL so they become top-level categories.
 *
 *  2. Multi-level hierarchy – parent_id refers to another subcategory
 *     (which itself has a non-null parent_id), creating a 3+ level deep
 *     tree that the dress-form UI does not support.
 *     Fix: walk up the chain until we reach a top-level ancestor and
 *     use that as the direct parent (flatten to max 2 levels).
 */
return new class extends Migration
{
    public function up(): void
    {
        // Guard: the parent_id column must exist before we can do anything.
        if (! Schema::hasColumn('dress_categories', 'parent_id')) {
            return;
        }

        // ── Step 1: fix orphaned subcategories ───────────────────────────
        // Build the full set of valid category IDs.
        $validIds = DB::table('dress_categories')->pluck('id')->all();

        if (! empty($validIds)) {
            // Any row whose parent_id is NOT in the valid-ID set is orphaned.
            DB::table('dress_categories')
                ->whereNotNull('parent_id')
                ->whereNotIn('parent_id', $validIds)
                ->update(['parent_id' => null]);
        }

        // ── Step 2: flatten multi-level hierarchy ────────────────────────
        // Repeatedly walk up until every subcategory's parent_id points
        // to a top-level category (one whose own parent_id IS NULL).
        $maxFlattenPasses = 10;

        for ($pass = 0; $pass < $maxFlattenPasses; $pass++) {
            // Reload the map after each pass (some parent_ids may have changed).
            // Map: id => parent_id  (only rows that are themselves subcategories)
            $subcatMap = DB::table('dress_categories')
                ->whereNotNull('parent_id')
                ->pluck('parent_id', 'id')   // [childId => parentId]
                ->all();

            // Collect all (childId => grandparentId) pairs that need fixing this pass.
            $fixes = [];
            foreach ($subcatMap as $childId => $parentId) {
                if (isset($subcatMap[$parentId])) {
                    $fixes[$childId] = $subcatMap[$parentId];
                }
            }

            if (empty($fixes)) {
                break;   // Nothing left to fix.
            }

            // Apply all fixes for this pass inside a single transaction.
            DB::transaction(function () use ($fixes) {
                foreach ($fixes as $childId => $grandparentId) {
                    DB::table('dress_categories')
                        ->where('id', $childId)
                        ->update(['parent_id' => $grandparentId]);
                }
            });
        }

        // ── Step 3: emergency safety net ─────────────────────────────────
        // After the loop, every parent_id should point to a top-level row.
        // In the unlikely event of a cycle or other edge-case, just null
        // out any remaining parent_id that still points to a subcategory.
        $remainingSubcatIds = DB::table('dress_categories')
            ->whereNotNull('parent_id')
            ->pluck('id')
            ->all();

        if (! empty($remainingSubcatIds)) {
            DB::table('dress_categories')
                ->whereNotNull('parent_id')
                ->whereIn('parent_id', $remainingSubcatIds)
                ->update(['parent_id' => null]);
        }
    }

    public function down(): void
    {
        // This migration only repairs data; there is no safe way to reverse it.
    }
};
