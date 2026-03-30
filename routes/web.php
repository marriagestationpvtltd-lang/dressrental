<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DressController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;

// ─── Public ──────────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dresses', [DressController::class, 'index'])->name('dresses.index');
Route::get('/dresses/{dress:slug}', [DressController::class, 'show'])->name('dresses.show');

// Availability check (open for AJAX)
Route::post('/dresses/{dress}/availability', [BookingController::class, 'checkAvailability'])
    ->name('booking.check-availability');

// ─── Guest ───────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Authenticated User ───────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/my-bookings', [UserDashboardController::class, 'bookings'])->name('bookings.index');
    Route::get('/my-bookings/{booking}', [UserDashboardController::class, 'showBooking'])->name('bookings.show');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

    // Payments
    Route::get('/payment/{booking}/initiate', [PaymentController::class, 'initiate'])->name('payment.initiate');
    Route::post('/payment/{booking}/esewa', [PaymentController::class, 'esewaInit'])->name('payment.esewa.init');
    Route::get('/payment/esewa/verify/{payment}', [PaymentController::class, 'esewaVerify'])->name('payment.esewa.verify');
    Route::post('/payment/{booking}/khalti', [PaymentController::class, 'khaltiInit'])->name('payment.khalti.init');
    Route::get('/payment/khalti/verify', [PaymentController::class, 'khaltiVerify'])->name('payment.khalti.verify');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failure', [PaymentController::class, 'failure'])->name('payment.failure');
});

// ─── Admin ────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Dresses
    Route::resource('dresses', Admin\DressController::class);
    Route::delete('/dresses/images/{image}', [Admin\DressController::class, 'deleteImage'])->name('dresses.images.destroy');

    // Bookings
    Route::resource('bookings', Admin\BookingController::class)->only(['index', 'show', 'update']);
    Route::post('/bookings/{booking}/status', [Admin\BookingController::class, 'updateStatus'])->name('bookings.update-status');

    // Payments
    Route::resource('payments', Admin\PaymentController::class)->only(['index', 'show']);
    Route::post('/payments/{payment}/refund', [Admin\PaymentController::class, 'refund'])->name('payments.refund');

    // Users
    Route::resource('users', Admin\UserController::class)->only(['index', 'show']);

    // Categories
    Route::resource('categories', Admin\CategoryController::class);

    // Settings
    Route::get('/settings', [Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [Admin\SettingsController::class, 'update'])->name('settings.update');

    // AI
    Route::post('/ai/describe-image', [Admin\AiController::class, 'describeImage'])->name('ai.describe-image');
});

