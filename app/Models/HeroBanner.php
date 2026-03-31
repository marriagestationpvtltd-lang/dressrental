<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HeroBanner extends Model
{
    protected $fillable = [
        'title',
        'media_type',
        'media_value',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    /** Scope: only active banners, ordered. */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    /** Return the full public URL for image banners, or null for YouTube banners. */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->media_type !== 'image') {
            return null;
        }

        return Storage::disk('public')->url($this->media_value);
    }

    /**
     * Return the YouTube embed URL.
     * Accepts full YouTube URLs, short youtu.be links, or plain video IDs.
     */
    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if ($this->media_type !== 'youtube') {
            return null;
        }

        $id = $this->extractYoutubeId($this->media_value);

        return $id ? 'https://www.youtube.com/embed/' . $id . '?autoplay=0&rel=0' : null;
    }

    public static function extractYoutubeId(string $input): ?string
    {
        $input = trim($input);

        // Plain video ID (11 chars)
        if (preg_match('/^[A-Za-z0-9_\-]{11}$/', $input)) {
            return $input;
        }

        // youtu.be/ID
        if (preg_match('/youtu\.be\/([A-Za-z0-9_\-]{11})/', $input, $m)) {
            return $m[1];
        }

        // youtube.com/watch?v=ID or /embed/ID or /shorts/ID
        if (preg_match('/(?:v=|\/embed\/|\/shorts\/)([A-Za-z0-9_\-]{11})/', $input, $m)) {
            return $m[1];
        }

        return null;
    }
}
