<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Dress;
use App\Models\Payment;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_dresses'   => Dress::count(),
            'available_dress' => Dress::available()->count(),
            'total_bookings'  => Booking::count(),
            'active_bookings' => Booking::where('status', 'active')->count(),
            'total_users'     => User::where('role', 'user')->count(),
            'total_revenue'   => Payment::where('status', 'completed')->sum('amount'),
            'pending_bookings'=> Booking::where('status', 'pending')->count(),
            'today_bookings'  => Booking::whereDate('created_at', today())->count(),
        ];

        $recentBookings = Booking::with(['user', 'dress'])
            ->latest()
            ->take(10)
            ->get();

        $recentPayments = Payment::with(['user', 'booking.dress'])
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        $monthlyRevenue = Payment::where('status', 'completed')
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Bookings due for return today or already overdue (status = active)
        $dueReturns = Booking::with(['user', 'dress'])
            ->where('status', 'active')
            ->whereDate('end_date', '<=', today())
            ->orderBy('end_date')
            ->get();

        // Bookings due for return in the next 3 days (status = active, not yet overdue)
        $upcomingReturns = Booking::with(['user', 'dress'])
            ->where('status', 'active')
            ->whereDate('end_date', '>', today())
            ->whereDate('end_date', '<=', today()->addDays(3))
            ->orderBy('end_date')
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'recentBookings', 'recentPayments', 'monthlyRevenue',
            'dueReturns', 'upcomingReturns'
        ));
    }
}
