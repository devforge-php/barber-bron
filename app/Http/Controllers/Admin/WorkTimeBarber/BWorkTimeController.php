<?php

namespace App\Http\Controllers\Admin\WorkTimeBarber;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkTimesRequest;
use App\Http\Resources\WorkTimeResource;
use App\Models\WorkSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class BWorkTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(): JsonResponse
{
    $page = request()->get('page', 1); // Sahifa raqami
    $cacheKey = "work_schedules_page_{$page}"; // Har sahifa uchun cache kaliti

    $schedules = Cache::remember($cacheKey, 60 * 5, function () {
        return WorkSchedule::with('barber')->latest()->paginate(10);
    });

    return response()->json(WorkTimeResource::collection($schedules)->response()->getData(true));
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(WorkTimesRequest $request): JsonResponse
    {
        $schedule = WorkSchedule::create($request->validated());

        Cache::forget('work_schedules');

        return response()->json(new WorkTimeResource($schedule), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WorkTimesRequest $request, string $id): JsonResponse
    {
        $schedule = WorkSchedule::findOrFail($id);
        $schedule->update($request->validated());

        Cache::forget('work_schedules');

        return response()->json(new WorkTimeResource($schedule));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $schedule = WorkSchedule::findOrFail($id);
        $schedule->delete();

        Cache::forget('work_schedules');

        return response()->json(null, 204);
    }
}