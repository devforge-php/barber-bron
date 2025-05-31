<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderNotifactions extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Yangi buyurtma!',
            'barber_id' => $this->booking->user->id,
            'barber_name' => $this->booking->user->name,
            'barber_lastname' => $this->booking->user->lastname,
            'barber_username' => $this->booking->user->username,
            'message' => $this->booking->user->name . ' yangi bron qildi.',
            'booking_id' => $this->booking->id,
            'user_name' => $this->booking->user_name,
            'user_phone' => $this->booking->user_phone,
            'booking_time' => $this->booking->booking_time,
     'services' => $this->booking->services->map(function ($service) {
    return [
        'id' => $service->id,
        'name_uz' => $service->name_uz,
        'name_ru' => $service->name_ru,
        'price' => $service->pivot->price,
    ];
}),

        ];
    }
}
