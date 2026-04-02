<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DressPricing extends Model
{
    protected $fillable = ['dress_id', 'days', 'price'];

    protected function casts(): array
    {
        return [
            'days'  => 'integer',
            'price' => 'decimal:2',
        ];
    }

    public function dress(): BelongsTo
    {
        return $this->belongsTo(Dress::class);
    }
}
