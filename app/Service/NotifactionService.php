<?php 


namespace App\Service;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;



class NotifactionService
{
    // Notificationlarni olish

public function getAllNotifications(User $user)
{
    if ($user->role === 'admin') {
        // Admin – hamma notificationlarni ko‘ra oladi
        return DatabaseNotification::query()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    // Barber – faqat o‘ziga tegishli notificationlar
    return $user->notifications()
        ->orderBy('created_at', 'desc')
        ->paginate(10);
}


    // Yangi notification yaratish
    public function createNotification($user, $data)
    {
        $user->notify(new \App\Notifications\OrderNotifactions($data)); // Yangi notification yuborish
    }

    // Notificationni o'chirish
    public function deleteNotification($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->delete();
        }
    }
}
