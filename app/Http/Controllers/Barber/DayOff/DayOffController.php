<?php
// App\Http\Controllers\Barber\DayOff\DayOffController.php

namespace App\Http\Controllers\Barber\DayOff;

use App\Http\Controllers\Controller;
use App\Http\Requests\DayOffRequest;
use App\Http\Resources\DayOffResouce;
use App\Service\BarberDayOffService;
use Illuminate\Http\JsonResponse;

class DayOffController extends Controller
{
    protected BarberDayOffService $dayOffService;

    public function __construct(BarberDayOffService $dayOffService)
    {
        $this->dayOffService = $dayOffService;
    }

    public function index(): JsonResponse
    {
        $dayOffs = $this->dayOffService->getAll(auth()->id());
        return response()->json(DayOffResouce::collection($dayOffs));
    }

    public function store(DayOffRequest $request): JsonResponse
    {
        $dayOff = $this->dayOffService->create($request->validated(), auth()->id());
        return response()->json(new DayOffResouce($dayOff), 201);
    }

    public function show(int $id): JsonResponse
    {
        $dayOff = $this->dayOffService->getById($id, auth()->id());
        return response()->json(new DayOffResouce($dayOff));
    }

    public function update(DayOffRequest $request, int $id): JsonResponse
    {
        $dayOff = $this->dayOffService->update($request->validated(), $id, auth()->id());
        return response()->json(new DayOffResouce($dayOff));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->dayOffService->delete($id, auth()->id());
        return response()->json(['message' => 'Day off deleted']);
    }
}