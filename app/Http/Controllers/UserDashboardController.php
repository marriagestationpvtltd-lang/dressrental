<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $recentBookings = $user->bookings()->with('dress.images')
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total'     => $user->bookings()->count(),
            'active'    => $user->bookings()->where('status', 'active')->count(),
            'completed' => $user->bookings()->where('status', 'completed')->count(),
            'pending'   => $user->bookings()->where('status', 'pending')->count(),
        ];

        return view('user.dashboard', compact('user', 'recentBookings', 'stats'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password'      => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function bookings()
    {
        $bookings = Auth::user()->bookings()
            ->with(['dress.images', 'payments'])
            ->latest()
            ->paginate(10);

        return view('user.bookings', compact('bookings'));
    }

    public function showBooking(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['dress.images', 'payments']);

        if (Schema::hasTable('booking_ornament') && Schema::hasTable('ornaments')) {
            $booking->load('ornaments');
        }

        return view('user.booking-show', compact('booking'));
    }
}
