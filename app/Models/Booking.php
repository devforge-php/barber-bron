<?php

namespace App\Models;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
 
    protected $guarded = [];
    public function services()
{
    return $this->belongsToMany(Service::class, 'booking_services')->withPivot('price')->withTimestamps();
}

public function user()
{
    return $this->belongsTo(User::class);
}
// App\Models\Booking.php
public function bookingServices()
{
    return $this->hasMany(\App\Models\BookingService::class, 'booking_id');
}

}
