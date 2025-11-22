<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TravelSchedule;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function availableSchedules(Request $request)
    {
        $query = TravelSchedule::query();

        if ($request->destination) {
            $query->where('destination', 'like', '%' . $request->destination . '%');
        }

        if ($request->date) {
            $query->whereDate('departure_datetime', $request->date);
        }

        $query->where('quota', '>', 0);

        return response()->json($query->get());
    }

    public function book(Request $request)
    {
        $validated = $request->validate([
            'travel_schedule_id' => 'required|exists:travel_schedules,id',
            'seats' => 'required|integer|min:1'
        ]);

        $schedule = TravelSchedule::findOrFail($validated['travel_schedule_id']);
            if ($schedule->quota < $validated['seats']) {
            return response()->json([
                'message' => 'Not enough quota. Available seats: ' . $schedule->quota
            ], 400);
        }

        $totalPrice = $schedule->price * $validated['seats'];

        // Kurangi quota
        $schedule->quota -= $validated['seats'];
        $schedule->save();

        // Buat booking
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'travel_schedule_id' => $schedule->id,
            'seats' => $validated['seats'],
            'total_price' => $totalPrice,
            'status' => 'pending_payment',
        ]);

        return response()->json([
            'message' => 'Booking created successfully',
            'data'    => $booking
        ], 201);
    }

    // Riwayat booking user
    public function history()
    {
        $history = Booking::with('schedule')->where('user_id', auth()->id())->get();
        return response()->json($history);
    }
}
