<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TravelSchedule;
use Illuminate\Http\Request;

class TravelScheduleController extends Controller
{
    public function index()
    {
        return TravelSchedule::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'destination' => 'required|string',
            'departure_datetime' => 'required|date',
            'quota' => 'required|integer|min:1',
            'price' => 'required|integer|min:1000'
        ]);

        $schedule = TravelSchedule::create($validated);

        return response()->json($schedule, 201);
    }

    public function show($id)
    {
        return TravelSchedule::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $schedule = TravelSchedule::findOrFail($id);

        $validated = $request->validate([
            'destination' => 'string',
            'departure_datetime' => 'date',
            'quota' => 'integer|min:1',
            'price' => 'integer|min:1000',
        ]);

        $schedule->update($validated);

        return response()->json($schedule);
    }

    public function destroy($id)
    {
        TravelSchedule::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }

    // Laporan jumlah penumpang
    public function passengerReport()
    {
        $report = TravelSchedule::withCount('bookings')->get();

        return response()->json($report);
    }

    // Riwayat penumpang dalam satu travel
    public function passengerDetail($id)
    {
        $schedule = TravelSchedule::with('bookings.user')->findOrFail($id);
        return response()->json($schedule);
    }
}
