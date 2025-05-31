<?php

namespace App\Http\Controllers\Admin\Statiska;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;


class StatiskaControlller extends Controller
{


public function getPaymentsByDateRange(Request $request)
{
    $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
    $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

    if (!$startDate || !$endDate) {
        return response()->json(['error' => 'start_date va end_date majburiy!'], 400);
    }

    // Notificationsni olib olamiz
    $notifications = DB::table('notifications')
        ->where('completed', 1)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->orderBy('created_at', 'asc')
        ->get();

    // Kunlik to'lovlar
    $dailyStats = [];

    // Har bir kun uchun
    $period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate->copy()->addDay());

    foreach ($period as $date) {
        $day = $date->format('Y-m-d');
        $dailyStats[$day] = 0;
    }

    // Endi notificationlarni o'qib chiqib har bir kun uchun umumiy narxni qo'shamiz
    foreach ($notifications as $notif) {
        $notifDate = Carbon::parse($notif->created_at)->format('Y-m-d');
        $data = json_decode($notif->data, true);

        if (!empty($data['services']) && is_array($data['services'])) {
            foreach ($data['services'] as $service) {
                $dailyStats[$notifDate] += floatval($service['price']);
            }
        }
    }

    // Chart uchun formatlab beramiz
    $chartData = [];
   foreach ($dailyStats as $date => $amount) {
    if ($amount > 0) {
        $chartData[] = [
            'date' => $date,
            'amount' => round($amount),
        ];
    }
}

    return response()->json([
        'total_payments' => number_format(array_sum($dailyStats), 0, ',', ' ') . ' UZS',
        'count' => count($notifications),
        'start_date' => $startDate->format('Y-m-d'),
        'end_date' => $endDate->format('Y-m-d'),
        'daily' => $chartData,
    ]);
}


}
