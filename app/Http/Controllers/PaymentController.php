<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_proof' => 'required|file|mimes:jpg,png,jpeg,pdf'
        ]);

        $booking = Booking::where('id', $validated['booking_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $path = $request->file('payment_proof')->store('payments', 'public');

        $amount = $booking->schedule->price;

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'paid_by' => auth()->id(),
            'payment_proof' => $path,
            'amount' => $amount,
            'status' => 'confirmed'
        ]);

        $booking->status = 'paid';
        $booking->save();

        return response()->json([
            'message' => 'Payment confirmed',
            'payment' => $payment
        ]);
    }

    // Invoice sederhana
    public function invoice($id)
    {
        $booking = Booking::with(['schedule', 'payment'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return response()->json([
            'invoice_number' => 'INV-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT),
            'booking' => $booking,
            'user' => auth()->user(),
        ]);
    }
}
