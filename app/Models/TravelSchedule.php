<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TravelSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'departure_city',
        'destination',
        'departure_datetime',
        'quota',
        'price',
        'category'
    ];

    protected $casts = [
        'departure_datetime' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // helper untuk remaining quota
    public function getRemainingQuotaAttribute()
    {
        $booked = $this->bookings()
            ->whereIn('status', ['pending_payment', 'paid'])
            ->sum('seats');

        return $this->quota - $booked;
    }
}
