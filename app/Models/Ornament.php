<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Ornament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_per_day',
        'deposit_amount',
        'category',
        'image_path',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price_per_day'  => 'decimal:2',
            'deposit_amount' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $ornament) {
            if (empty($ornament->slug)) {
                $ornament->slug = Str::slug($ornament->name) . '-' . Str::random(5);
            }
        });
    }

    public function dresses(): BelongsToMany
    {
        return $this->belongsToMany(Dress::class, 'dress_ornament');
    }

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_ornament')
            ->withPivot('price_per_day', 'deposit_amount', 'subtotal')
            ->withTimestamps();
    }

    public function recommendedForCategories(): BelongsToMany
    {
        return $this->belongsToMany(
            DressCategory::class,
            'category_ornament_recommendations',
            'ornament_id',
            'dress_category_id'
        )->withPivot('sort_order');
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            return Storage::disk('public')->url($this->image_path);
        }
        return asset('images/dress-placeholder.svg');
    }

    public static function categoryLabel(string $category): string
    {
        return match ($category) {
            'jewelry'          => 'Jewelry',
            'hair_accessories' => 'Hair Accessories',
            'footwear'         => 'Footwear',
            'handbag'          => 'Handbag',
            default            => 'Other',
        };
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}
