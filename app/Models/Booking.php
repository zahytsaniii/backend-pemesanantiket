<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'travel_schedule_id',
        'seats',
        'total_price',
        'status', // pending_payment | paid | cancelled
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(TravelSchedule::class, 'travel_schedule_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
