<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dress_id',
        'start_date',
        'end_date',
        'bs_start_date',
        'bs_end_date',
        'total_days',
        'rental_amount',
        'deposit_amount',
        'total_amount',
        'advance_amount',
        'fine_amount',
        'status',
        'notes',
        'paid_at',
        'returned_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date'     => 'date',
            'end_date'       => 'date',
            'rental_amount'  => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'total_amount'   => 'decimal:2',
            'advance_amount' => 'decimal:2',
            'fine_amount'    => 'decimal:2',
            'paid_at'        => 'datetime',
            'returned_at'    => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dress(): BelongsTo
    {
        return $this->belongsTo(Dress::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function completedPayments(): HasMany
    {
        return $this->hasMany(Payment::class)->where('status', 'completed');
    }

    public function getTotalPaidAttribute(): float
    {
        return (float) $this->completedPayments()->sum('amount');
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'yellow',
            'paid'      => 'blue',
            'active'    => 'green',
            'returned'  => 'purple',
            'completed' => 'gray',
            'cancelled' => 'red',
            default     => 'gray',
        };
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
