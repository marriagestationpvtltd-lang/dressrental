<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DressSize extends Model
{
    protected $fillable = ['dress_id', 'size'];

    public function dress(): BelongsTo
    {
        return $this->belongsTo(Dress::class);
    }
}
