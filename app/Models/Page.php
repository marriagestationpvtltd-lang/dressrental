<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'content', 'status', 'sort_order'];

    protected static function booted(): void
    {
        static::creating(function (self $page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
