<?php

namespace App\Http\Controllers\Users\BarbersDate;

use Illuminate\Routing\Controller;
use App\Models\WorkSchedule;
use App\Models\DayOff;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DateController extends Controller
{
    public function getAvailableTimesForDate(Request $request, int $userId): JsonResponse
    {
        $barber = \App\Models\User::find($userId);
        if (!$barber) {
            return response()->json(['error' => 'Barber not found'], 404);
        }

        $date = $request->query('date');
        if (!$date || !@Carbon::hasFormat($date, 'Y-m-d')) {
            return response()->json(['error' => 'Valid date is required (format: Y-m-d)'], 400);
        }

        $parsedDate = Carbon::parse($date)->toDateString();

        $isDayOff = \App\Models\DayOff::where('user_id', $userId)
            ->where('day_off', $parsedDate)
            ->exists();
        if ($isDayOff) {
            return response()->json([
                'barber_name' => $barber->name,
                'available_times' => [],
                'message' => 'Barber has a day off on this date',
            ]);
        }

        $schedule = \App\Models\WorkSchedule::where('user_id', $userId)
            ->where('date', $parsedDate)
            ->first();

        if (!$schedule) {
            return response()->json([
                'barber_name' => $barber->name,
                'available_times' => [],
                'message' => 'No work schedule for this date',
            ]);
        }

        // Barcha bookinglarni olish (ular xizmatlar bilan)
        $bookings = \App\Models\Booking::with('bookingServices.service')
            ->where('user_id', $userId)
            ->whereDate('booking_time', $parsedDate)
            ->get();

        // Band vaqtlar ro‘yxati
        $bookedSlots = [];
        foreach ($bookings as $booking) {
            $start = Carbon::parse($booking->booking_time);

            foreach ($booking->bookingServices as $bs) {
                $min = intval($bs->service->minut);
                $end = $start->copy()->addMinutes($min);

                $bookedSlots[] = [
                    'start' => $start->copy(),
                    'end' => $end
                ];

                $start = $end->copy();
            }
        }

        // Jadval vaqti
        $startTime = Carbon::parse("$parsedDate " . $schedule->start_time);
        $endTime = Carbon::parse("$parsedDate " . $schedule->end_time);

        $slotDuration = 30; // Har bir xizmat uchun ajratiladigan minimal vaqt (minutda)

        $availableTimes = [];

        while ($startTime->lt($endTime)) {
            $slotStart = $startTime->copy();
            $slotEnd = $slotStart->copy()->addMinutes($slotDuration);

            // Band vaqtlar bilan to‘qnashganini tekshiramiz
            $conflict = false;
            foreach ($bookedSlots as $slot) {
                if (
                    $slotStart->lt($slot['end']) &&
                    $slotEnd->gt($slot['start'])
                ) {
                    $conflict = true;
                    break;
                }
            }

            if (!$conflict) {
                $availableTimes[] = $slotStart->format('H:i');
            }

            $startTime->addMinutes($slotDuration);
        }

        return response()->json([
            'barber_name' => $barber->name,
            'available_times' => $availableTimes
        ]);
    }
}
