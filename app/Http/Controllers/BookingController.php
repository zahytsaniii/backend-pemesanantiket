<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\TravelSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function availableSchedules(Request $request)
    {
        $query = TravelSchedule::query();

        // GLOBAL SEARCH (kategori, harga, quota, destination)
        if ($request->filled('query')) {
            $search = $request->query('query');
            $query->where(function ($q) use ($search) {
                $q->where('destination', 'ilike', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")
                ->orWhere('price', 'like', "%{$search}%")
                ->orWhere('quota', 'like', "%{$search}%");
            });
        }

        // DESTINATION (opsional)
        if ($request->filled('destination')) {
            $query->where('destination', 'ilike', '%' . $request->destination . '%');
        }

        // DATE (opsional)
        if ($request->filled('date')) {
            $query->whereDate('departure_datetime', $request->date);
        }

        // QUOTA (opsional, gunakan >= agar fleksibel)
        if ($request->filled('quota')) {
            $query->where('quota', '>=', $request->quota);
        }

        // RESULT HARUS ADA KURSI
        // $query->where('quota', '>', 0);

        return response()->json($query->get());
    }

    public function book(Request $request)
    {
        $validated = $request->validate([
            'travel_schedule_id' => 'required|exists:travel_schedules,id',
            'seats' => 'required|integer|min:1'
        ]);

        return DB::transaction(function () use ($validated) {

            // LOCK row agar tidak diambil oleh user lain bersamaan
            $schedule = TravelSchedule::where('id', $validated['travel_schedule_id'])
                ->lockForUpdate()
                ->firstOrFail();

            // Kuota 0 → langsung gagal
            if ($schedule->quota <= 0) {
                return response()->json([
                    'message' => 'Tiket sudah habis untuk jadwal ini'
                ], 400);
            }

            // Kuota tidak cukup
            if ($schedule->quota < $validated['seats']) {
                return response()->json([
                    'message' => 'Jumlah kursi melebihi kuota tersedia. Sisa kursi: ' . $schedule->quota
                ], 400);
            }

            // Hitung total harga
            $totalPrice = $schedule->price * $validated['seats'];

            // Kurangi kuota secara aman
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
                'data' => $booking
            ], 201);
        });
    }

    // Riwayat booking user
    public function history()
    {
        $history = Booking::with('schedule')->where('user_id', auth()->id())->get();
        return response()->json($history);
    }

    public function getOrder($id)
    {
        $booking = Booking::with('schedule')->where('id', $id)->first();

        if (!$booking) {
            return response()->json([
                'message' => 'Booking tidak ditemukan'
            ], 404);
        }

        // Format response agar frontend mudah konsumsi
        $data = [
            'id' => $booking->id,
            'seats' => $booking->seats,
            'total_price' => $booking->total_price,
            'status' => $booking->status,
            'route' => [
                'departure_city' => $booking->schedule->departure_city,
                'destination' => $booking->schedule->destination,
                'date' => $booking->schedule->departure_datetime->format('Y-m-d'),
                'time' => $booking->schedule->departure_datetime->format('H:i'),
                'price' => $booking->schedule->price,
            ],
        ];

        return response()->json([
            'message' => 'Booking ditemukan',
            'data' => $data
        ], 200);
    }

    public function cancelBooking($id)
    {
        $booking = Booking::find($id);

        if (!$booking || $booking->user_id != auth()->id()) {
            return response()->json(['message' => 'Booking tidak ditemukan atau tidak memiliki izin'], 404);
        }

        // hanya bisa batalkan jika status masih pending_payment
        if ($booking->status !== 'pending_payment') {
            return response()->json(['message' => 'Pesanan tidak bisa dibatalkan'], 400);
        }

        // kembalikan kuota
        $schedule = $booking->schedule;
        $schedule->quota += $booking->seats;
        $schedule->save();

        // ubah status booking menjadi cancelled
        $booking->status = 'cancelled';
        $booking->save();

        return response()->json(['message' => 'Pesanan dibatalkan'], 200);
    }

    public function payBooking($id)
    {
        $booking = Booking::with('schedule')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $amount = $booking->schedule->price * $booking->seats;

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'paid_by' => auth()->id(),
            'amount' => $amount,
            'status' => 'confirmed',
        ]);

        $booking->status = 'paid';
        $booking->save();

        return response()->json([
            'message' => 'Pembayaran berhasil',
            'payment' => $payment,
            'booking' => $booking
        ]);
    }


}
