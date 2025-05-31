<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function barber()
    {
       return $this->belongsTo(User::class, 'user_id');
    }
    public function bookings()
{
    return $this->belongsToMany(Booking::class, 'booking_service')->withPivot('price');
}
public function bookingServices()
{
    return $this->hasMany(BookingService::class);
}
}
