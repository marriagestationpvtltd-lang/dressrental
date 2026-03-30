<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Dress;
use Carbon\Carbon;

class BookingService
{
    public function isAvailable(int $dressId, Carbon $startDate, Carbon $endDate): bool
    {
        return ! Booking::where('dress_id', $dressId)
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->where('start_date', '<=', $endDate->format('Y-m-d'))
            ->where('end_date', '>=', $startDate->format('Y-m-d'))
            ->exists();
    }

    public function getBookedDateRanges(int $dressId): array
    {
        return Booking::where('dress_id', $dressId)
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->get(['start_date', 'end_date'])
            ->map(fn ($b) => [
                'start' => $b->start_date->format('Y-m-d'),
                'end'   => $b->end_date->format('Y-m-d'),
            ])
            ->toArray();
    }

    public function calculateRentalAmount(Dress $dress, Carbon $startDate, Carbon $endDate): array
    {
        $totalDays     = $startDate->diffInDays($endDate) + 1;
        $rentalAmount  = $totalDays * $dress->price_per_day;
        $depositAmount = $dress->deposit_amount;
        $totalAmount   = $rentalAmount + $depositAmount;
        $advancePercent = setting('advance_payment_percentage', 50);
        $advanceAmount  = round($totalAmount * ($advancePercent / 100), 2);

        return [
            'total_days'     => $totalDays,
            'rental_amount'  => $rentalAmount,
            'deposit_amount' => $depositAmount,
            'total_amount'   => $totalAmount,
            'advance_amount' => $advanceAmount,
        ];
    }

    public function createBooking(array $data): Booking
    {
        $dress     = Dress::findOrFail($data['dress_id']);
        $startDate = Carbon::parse($data['start_date']);
        $endDate   = Carbon::parse($data['end_date']);

        if (! $this->isAvailable($dress->id, $startDate, $endDate)) {
            throw new \Exception('The dress is not available for the selected dates.');
        }

        $amounts = $this->calculateRentalAmount($dress, $startDate, $endDate);

        $bsStart = NepaliCalendarService::carbonToBsString($startDate);
        $bsEnd   = NepaliCalendarService::carbonToBsString($endDate);

        return Booking::create([
            'user_id'        => $data['user_id'],
            'dress_id'       => $dress->id,
            'start_date'     => $startDate->format('Y-m-d'),
            'end_date'       => $endDate->format('Y-m-d'),
            'bs_start_date'  => $bsStart,
            'bs_end_date'    => $bsEnd,
            'total_days'     => $amounts['total_days'],
            'rental_amount'  => $amounts['rental_amount'],
            'deposit_amount' => $amounts['deposit_amount'],
            'total_amount'   => $amounts['total_amount'],
            'advance_amount' => $amounts['advance_amount'],
            'notes'          => $data['notes'] ?? null,
            'status'         => 'pending',
        ]);
    }
}
