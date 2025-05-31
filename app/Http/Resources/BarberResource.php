<?php

namespace App\Http\Resources;

use App\Models\Booking;
use App\Models\DayOff;
use App\Models\Review;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class BarberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $averageRating = Review::where('user_id', $this->id)->avg('rating');
        $averageRating = round($averageRating);

        $reviewCount = Review::where('user_id', $this->id)->count();

        $tomorrow = now()->addDay()->toDateString();

        $workSchedule = WorkSchedule::where('user_id', $this->id)
            ->where('date', $tomorrow)
            ->first();

        $availableTimes = [];

        if ($workSchedule) {
            $start = (int) date('H', strtotime($workSchedule->start_time));
            $end = (int) date('H', strtotime($workSchedule->end_time));

            for ($i = $start; $i <= $end; $i++) {
                $time = str_pad($i, 2, '0', STR_PAD_LEFT) . ":00";

                $isBooked = Booking::where('user_id', $this->id)
                    ->whereDate('booking_time', $tomorrow)
                    ->whereTime('booking_time', $time)
                    ->exists();

                if (!$isBooked) {
                    $availableTimes[] = $time;
                }
            }
        }

        $topReviews = Review::where('user_id', $this->id)
            ->orderByDesc('rating')
            ->limit(10)
            ->get()
            ->map(function ($review) {
                return [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'name' => $review->name,
                    'phone' => $review->phone,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at->toDateTimeString(),
                ];
            });

        $dayOffs = DayOff::where('user_id', $this->id)
            ->pluck('day_off')
            ->map(fn($date) => Carbon::parse($date)->toDateString())
            ->toArray();

        return [
            'id' => $this->id,
'image' => $this->image ? asset('storage/' . $this->image) : null,


            'username' => $this->username,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'phone' => $this->phone,
            'bio_uz' => $this->text_uz,
            'bio_ru' => $this->text_ru,
            'role' => $this->role,
            'rating' => $averageRating ?? 0,
            'review_count' => $reviewCount,
            'available_times_tomorrow' => $availableTimes,
            'top_reviews' => $topReviews,
            'day_offs' => $dayOffs,
        ];
    }
}
