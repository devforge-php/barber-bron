<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CometController extends Controller
{
        // Izoh qoldirish
    public function store(Request $request)
    {
        // Validatsiya
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'exists:users,id'], // Barber user_id
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Ma'lumotlarni saqlash
        $review = Review::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Izoh muvaffaqiyatli qo\'shildi.',
            'data' => $review
        ], 201);
    }
}
