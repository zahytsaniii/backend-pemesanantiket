<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelScheduleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // Role: Admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/test', function () {
            return 'Halo Admin';
        });
        
        // CRUD Travel Schedule
        Route::get('/schedules',            [TravelScheduleController::class, 'index']);
        Route::post('/schedules',           [TravelScheduleController::class, 'store']);
        Route::get('/schedules/{id}',       [TravelScheduleController::class, 'show']);
        Route::put('/schedules/{id}',       [TravelScheduleController::class, 'update']);
        Route::delete('/schedules/{id}',    [TravelScheduleController::class, 'destroy']);

        // Reports
        Route::get('/reports/passengers',           [TravelScheduleController::class, 'passengerReport']);
        Route::get('/reports/passengers/{id}',      [TravelScheduleController::class, 'passengerDetail']);
    });

    // Role: User
    Route::middleware('role:user')->group(function () {
        Route::get('/user/test', function () {
            return 'Halo User';
        });

        // View available schedules
        Route::get('/schedules', [BookingController::class, 'availableSchedules']);

        // Book a ticket
        Route::post('/book', [BookingController::class, 'book']);

        // Get booking Id
        Route::get('/bookings/{id}', [BookingController::class, 'getOrder']);
        Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancelBooking']);
        Route::post('/bookings/{id}/pay', [BookingController::class, 'payBooking']);

        // Booking history
        Route::get('/history', [BookingController::class, 'history']);

        // Payment process
        Route::post('/payment', [PaymentController::class, 'pay']);

        // Invoice
        Route::get('/invoice/{id}', [PaymentController::class, 'invoice']);
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



