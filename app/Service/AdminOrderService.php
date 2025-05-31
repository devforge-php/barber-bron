<?php

namespace App\Service;

use Illuminate\Support\Facades\Cache;
use App\Models\Booking;

class AdminOrderService
{
    public function get()
    {
        return Cache::rememberForever('booking_all', function () {
            return Booking::with('user', 'services')->get();
        });
    }

    public function findWithServices($id)
    {
        return Cache::rememberForever("booking_$id", function () use ($id) {
            return Booking::with('user', 'services')->find($id);
        });
    }

    public function forgetCaches($id)
    {
        Cache::forget("booking_$id");
        Cache::forget('booking_all');
    }
}