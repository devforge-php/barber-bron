<?php

namespace App\Service;

use App\Models\Review;
use App\Http\Resources\CommetResource;
use Illuminate\Support\Facades\Cache;

class CommetService
{
    public function getAll()
    {
        $barberId = auth()->id();

        return Cache::remember("barber:{$barberId}:comments", 60 * 5, function () use ($barberId) {
            $reviews = Review::where('user_id', $barberId)->latest()->get();
            return CommetResource::collection($reviews);
        });
    }

    public function getById($id)
    {
        $barberId = auth()->id();

        return Cache::remember("barber:{$barberId}:comment:{$id}", 60 * 5, function () use ($barberId, $id) {
            $review = Review::where('id', $id)
                            ->where('user_id', $barberId)
                            ->firstOrFail();
            return new CommetResource($review);
        });
    }

    public function delete($id)
    {
        $barberId = auth()->id();

        $review = Review::where('id', $id)
                        ->where('user_id', $barberId)
                        ->firstOrFail();

        $review->delete();

        // 🔁 Cache'ni tozalaymiz
        Cache::forget("barber:{$barberId}:comments");
        Cache::forget("barber:{$barberId}:comment:{$id}");

        return response()->json(['message' => 'Comment muvaffaqiyatli o‘chirildi.']);
    }
}
