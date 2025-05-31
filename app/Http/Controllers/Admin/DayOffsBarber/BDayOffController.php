<?php

namespace App\Http\Controllers\Admin\DayOffsBarber;

use App\Http\Controllers\Controller;
use App\Http\Requests\DayOffRequestAdmin;
use App\Http\Resources\DayOffResouce;
use App\Models\DayOff;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class BDayOffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(): JsonResponse
{
    $page = request()->get('page', 1); // Sahifa raqamini olamiz
    $cacheKey = "day_offs_page_{$page}"; // Har bir sahifaga alohida kalit

    $dayOffs = Cache::remember($cacheKey, 60 * 5, function () {
        return DayOff::with('barber')->latest()->paginate(10);
    });

    return response()->json($dayOffs); // Agar xohlasang DayOffResource qo‘shsa ham bo‘ladi
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(DayOffRequestAdmin $request): JsonResponse
    {
        $dayOff = DayOff::create($request->validated());

        // Cache ni tozalash
        Cache::forget('day_offs');

        return response()->json(new DayOffResouce($dayOff), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DayOffRequestAdmin $request, string $id): JsonResponse
    {
        $dayOff = DayOff::findOrFail($id);
        $dayOff->update($request->validated());

        // Cache ni tozalash
        Cache::forget('day_offs');

        return response()->json(new DayOffResouce($dayOff));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $dayOff = DayOff::findOrFail($id);
        $dayOff->delete();

        // Cache ni tozalash
        Cache::forget('day_offs');

        return response()->json(null, 204);
    }
}