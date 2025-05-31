<?php

namespace App\Service;

use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Str;
use App\Notifications\OrderNotifactions;
use Illuminate\Support\Facades\DB;

class AdminBronService
{
  public function adminBronPost(array $validated)
{
    return DB::transaction(function () use ($validated) {
        // Booking yaratish
        $booking = Booking::create([
            'user_id' => $validated['user_id'],
            'user_name' => $validated['user_name'],
            'user_phone' => $validated['user_phone'],
            'booking_time' => $validated['booking_time'],
        ]);

        // Xizmatlarni bookingga biriktirish
        $serviceIds = collect($validated['services'])->pluck('id')->toArray();
        $services = Service::whereIn('id', $serviceIds)->get();

        foreach ($services as $service) {
            BookingService::create([
                'booking_id' => $booking->id,
                'service_id' => $service->id,
                'price' => $service->price,
            ]);
        }

        // Notification malumotlarini to'plash
        $notificationData = [
            'title' => 'Yangi buyurtma!',
            'barber_id' => $booking->user_id,
            'barber_name' => $booking->user_name,
            'barber_lastname' => '', // Agar kerak bo‘lsa
            'barber_username' => '', // Agar kerak bo‘lsa
            'message' => "{$booking->user_name} yangi bron qildi.",
            'booking_id' => $booking->id,
            'user_name' => $booking->user_name,
            'user_phone' => $booking->user_phone,
            'booking_time' => $booking->booking_time,
            'services' => $services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name_uz' => $service->name_uz,
                    'name_ru' => $service->name_ru,
                    'price' => $service->price,
                ];
            })->toArray(),
        ];

        // Faqat bazaga yozamiz
        DB::table('notifications')->insert([
            'id' => Str::uuid(),
            'type' => 'App\\Notifications\\OrderNotifactions',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $booking->user_id,
            'data' => json_encode($notificationData),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $booking;
    });
}
}
