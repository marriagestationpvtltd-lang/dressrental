<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DressCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'is_active',
        'sort_order',
        'parent_id',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ── Relationships ────────────────────────────────────────────

    public function parent(): BelongsTo
    {
        return $this->belongsTo(DressCategory::class, 'parent_id');
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(DressCategory::class, 'parent_id')->orderBy('sort_order');
    }

    public function activeSubcategories(): HasMany
    {
        return $this->hasMany(DressCategory::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function dresses(): HasMany
    {
        return $this->hasMany(Dress::class, 'category_id');
    }

    public function activeDresses(): HasMany
    {
        return $this->hasMany(Dress::class, 'category_id')->where('status', 'available');
    }

    public function recommendedOrnaments(): BelongsToMany
    {
        return $this->belongsToMany(
            Ornament::class,
            'category_ornament_recommendations',
            'dress_category_id',
            'ornament_id'
        )->withPivot('sort_order')->orderByPivot('sort_order');
    }

    // ── Scopes ───────────────────────────────────────────────────

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    // ── Helpers ──────────────────────────────────────────────────

    public function isTopLevel(): bool
    {
        return is_null($this->parent_id);
    }
}
