<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotifactionResource;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BarberWorksController extends Controller
{
    public function service($id)
    {
        $user = User::with(['service' => function ($query) {
            $query->select('id', 'user_id', 'name_uz', 'name_ru', 'description_uz', 'description_ru', 'price', 'minut');
        }])->select('id', 'name')->find($id);

        if (!$user) {
            return response()->json(['message' => 'Foydalanuvchi topilmadi'], 404);
        }

        return response()->json([
            'user' => $user->only(['id', 'name']),
            'service' => $user->service
        ]);
    }

    public function worktime($id)
    {
        $user = User::select('id', 'name')->find($id);

        if (!$user) {
            return response()->json(['message' => 'Foydalanuvchi topilmadi'], 404);
        }

        $user->load(['WorkSchedules' => function ($query) {
            $query->select('id', 'user_id', 'date', 'start_time', 'end_time');
        }]);

        return response()->json([
            'user' => $user->only(['id', 'name']),
            'work_time' => $user->WorkSchedules
        ]);
    }
   public function dayoff($id)
{
    $user = User::select('id', 'name')->find($id);

    if (!$user) {
        return response()->json(['message' => 'Foydalanuvchi topilmadi'], 404);
    }

    $user->load(['dayoff' => function ($query) {
        $query->select('id', 'user_id', 'day_off');
    }]);

    return response()->json([
        'user' => $user->only(['id', 'name']),
        'day_offs' => $user->dayoff
    ]);
}

public function barberserviceday(Request $request)
{
    $request->validate([
        'date' => 'required|date', // misol: 2025-05-31
    ]);

    $date = $request->input('date');

    $notifications = Notification::where('active', true)
        ->whereDate(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.booking_time'))"), $date)
        ->get();

    return NotifactionResource::collection($notifications);
}



}
