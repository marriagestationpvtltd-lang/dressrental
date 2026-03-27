<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Dress extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'size',
        'price_per_day',
        'deposit_amount',
        'status',
        'is_featured',
        'color',
        'brand',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'price_per_day'  => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'is_featured'    => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $dress) {
            if (empty($dress->slug)) {
                $dress->slug = Str::slug($dress->name) . '-' . Str::random(5);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(DressCategory::class, 'category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(DressImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): ?DressImage
    {
        return $this->images()->where('is_primary', true)->first()
            ?? $this->images()->first();
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function activeBookings(): HasMany
    {
        return $this->hasMany(Booking::class)
            ->whereNotIn('status', ['cancelled', 'completed']);
    }

    public function isAvailableFor(\Carbon\Carbon $start, \Carbon\Carbon $end): bool
    {
        return ! $this->activeBookings()
            ->where('start_date', '<=', $end->format('Y-m-d'))
            ->where('end_date', '>=', $start->format('Y-m-d'))
            ->exists();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $img = $this->primaryImage();
        if ($img) {
            return asset('storage/' . $img->image_path);
        }
        return asset('images/dress-placeholder.svg');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
