<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
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

    /** All sizes this dress is available in (via dress_sizes table). */
    public function availableSizes(): HasMany
    {
        return $this->hasMany(DressSize::class)->orderByRaw("FIELD(size,'XS','S','M','L','XL','XXL','Free Size')");
    }

    /** Per-duration pricing tiers for this dress. */
    public function pricings(): HasMany
    {
        return $this->hasMany(DressPricing::class)->orderBy('days');
    }

    /**
     * Returns the list of available sizes, falling back to the legacy single-size field.
     *
     * @return string[]
     */
    public function getSizesListAttribute(): array
    {
        if ($this->relationLoaded('availableSizes') && $this->availableSizes->isNotEmpty()) {
            return $this->availableSizes->pluck('size')->toArray();
        }
        $loaded = $this->availableSizes()->pluck('size')->toArray();
        if ($loaded) {
            return $loaded;
        }
        return $this->size ? [$this->size] : [];
    }

    /**
     * Returns a human-readable size label (e.g. "S / M / L") for display in cards and badges.
     * Uses the loaded availableSizes relation when available, falls back to the legacy size column.
     */
    public function getSizeDisplayAttribute(): string
    {
        if ($this->relationLoaded('availableSizes') && $this->availableSizes->isNotEmpty()) {
            return $this->availableSizes->pluck('size')->join(' / ');
        }
        return $this->size ?? '';
    }

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
        if ($this->relationLoaded('images')) {
            return $this->images->firstWhere('is_primary', true) ?? $this->images->first();
        }

        return $this->images()->where('is_primary', true)->first()
            ?? $this->images()->first();
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function ornaments(): BelongsToMany
    {
        return $this->belongsToMany(Ornament::class, 'dress_ornament');
    }

    public function activeBookings(): HasMany
    {
        return $this->hasMany(Booking::class)
            ->whereNotIn('status', ['cancelled', 'completed']);
    }

    public function isAvailableFor(\Carbon\Carbon $start, \Carbon\Carbon $end, ?string $size = null): bool
    {
        $query = $this->activeBookings()
            ->where('start_date', '<=', $end->format('Y-m-d'))
            ->where('end_date', '>=', $start->format('Y-m-d'));

        if ($size !== null) {
            $query->where('booked_size', $size);
        }

        return ! $query->exists();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $img = $this->primaryImage();
        if ($img) {
            return Storage::disk('public')->url($img->image_path);
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
