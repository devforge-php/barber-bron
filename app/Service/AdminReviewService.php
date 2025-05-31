<?php

namespace App\Service;

use App\Models\Review;
use Illuminate\Support\Facades\Cache;

class AdminReviewService
{
public function getPaginated()
{
    $page = request('page', 1); // querydan sahifa raqamini olish
    return Cache::remember("reviews_page_{$page}", now()->addMinutes(10), function () {
        return Review::with('barber')->orderBy('created_at', 'desc')->paginate(10);
    });
}



    public function find($id)
    {
        return Cache::rememberForever("review_$id", function () use ($id) {
            return Review::with('barber')->find($id); // <- Eager load 'barber'
        });
    }

    public function forgetCaches($id)
    {
        Cache::forget("review_$id");
        Cache::forget('review_all');
    }

    public function delete($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        $this->forgetCaches($id); // <- Cache o'chirilsin
    }
    public function getByUserId($userId)
{
    return Review::with('barber')
        ->where('user_id', $userId)
        ->get();
}
}
