<?php

// App\Http\Controllers\Barber\WorkTime\WorkTimeController.php

namespace App\Http\Controllers\Barber\WorkTime;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkTimeRequest;
use App\Http\Resources\WorkTimeResource;
use App\Service\BarBerWorkTime;
use Illuminate\Http\JsonResponse;

class WorkTimeController extends Controller
{
    protected BarBerWorkTime $workTimeService;

    public function __construct(BarBerWorkTime $workTimeService)
    {
        $this->workTimeService = $workTimeService;
    }

    public function index(): JsonResponse
    {
        $schedules = $this->workTimeService->getAll(auth()->id());
        return response()->json(WorkTimeResource::collection($schedules));
    }

    public function store(WorkTimeRequest $request): JsonResponse
    {
        $schedule = $this->workTimeService->create($request->validated(), auth()->id());
        return response()->json(new WorkTimeResource($schedule), 201);
    }

    public function show(int $id): JsonResponse
    {
        $schedule = $this->workTimeService->getById($id, auth()->id());
        return response()->json(new WorkTimeResource($schedule));
    }

    public function update(WorkTimeRequest $request, int $id): JsonResponse
    {
        $schedule = $this->workTimeService->update($request->validated(), $id, auth()->id());
        return response()->json(new WorkTimeResource($schedule));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->workTimeService->delete($id, auth()->id());
        return response()->json(['message' => 'Work time deleted']);
    }
}