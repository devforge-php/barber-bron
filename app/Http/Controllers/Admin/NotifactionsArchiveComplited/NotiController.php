<?php

namespace App\Http\Controllers\Admin\NotifactionsArchiveComplited;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotifactionResource;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotiController extends Controller
{
    // 🗃 Arxivga tushganlar (active = 0, completed = 0)
   public function archive()
{
    $user = Auth::user();

    $query = Notification::where('active', false)
                         ->where('completed', false);

    if ($user->role === 'barber') {
        $query->where('notifiable_id', $user->id)
              ->where('notifiable_type', get_class($user));
    }

    $notifications = $query->get();

    return NotifactionResource::collection($notifications);
}

    // ✅ Completed (completed = 1, active = 0)
    public function completed()
    {
        $user = Auth::user();

        $query = Notification::where('completed', true)
                             ->where('active', false);

        if ($user->role === 'barber') {
            $query->where('notifiable_id', $user->id)
                  ->where('notifiable_type', get_class($user));
        }

        $notifications = $query->get();

        return NotifactionResource::collection($notifications);
    }

    // Arxiv uchun umumiy
    public function arcomapi()
    {
        $notifications = Notification::where('active', false)
                                     ->where('completed', false)
                                     ->get();

        return NotifactionResource::collection($notifications);
    }

    // Qidiruv
    public function search(Request $request)
    {
        $query = Notification::query();

        if ($request->filled('user_name')) {
            $query->where('data->user_name', 'like', '%' . $request->user_name . '%');
        }

        if ($request->filled('user_phone')) {
            $query->where('data->user_phone', 'like', '%' . $request->user_phone . '%');
        }

        $query->orderBy('created_at', 'desc');

        $notifications = $query->paginate(10);

        return response()->json(NotifactionResource::collection($notifications)->response()->getData(true));
    }
}
