<?php

namespace App\Http\Controllers\Admin\Notifactions;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotifactionResource;
use App\Models\Notification;
use App\Service\NotifactionService;
use Illuminate\Http\Request;

class NotifactionController extends Controller
{
    protected $notifactionService;

    public function __construct(NotifactionService $notifactionService)
    {
        $this->notifactionService = $notifactionService;
    }

    // Notificationlarni olish
public function index(Request $request)
{
    $user = auth()->user(); // Foydalanuvchini olish
    $notifications = $this->notifactionService->getAllNotifications($user);

    return NotifactionResource::collection($notifications); // Chiroyli javob
}

    // Bitta notificationni ko'rsatish
    public function show(string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);

        // Agar hali o'qilmagan bo'lsa, o'qilgan deb belgilaymiz
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return new NotifactionResource($notification);
    }


    // Notificationni o'chirish
    public function destroy(string $id)
    {
        $this->notifactionService->deleteNotification($id);
        return response()->json(['message' => 'Notification deleted successfully']);
    }
    public function markAsCompleted($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['completed' => true]);

        return response()->json(['message' => 'Notification marked as completed']);
    }
    
    public function archiveNotification($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['active' => false]);

        return response()->json(['message' => 'Notification archived']);
    }
}
