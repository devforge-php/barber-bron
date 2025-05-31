<?php

namespace App\Http\Controllers\Barber\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\BarberService;
use App\Http\Resources\BarberServiceResoure;
use App\Http\Requests\BarberRequestService;

class ServiceController extends Controller
{
    protected $barberService;

    public function __construct(BarberService $barberService)
    {
        $this->barberService = $barberService;
    }

    public function index()
    {
        $userId = auth()->id();
        return BarberServiceResoure::collection(
            $this->barberService->getAllByUser($userId)
        );
    }

    public function store(BarberRequestService $request)
    {
        $validated = $request->validated();

        $service = $this->barberService->create(
            array_merge($validated, ['user_id' => auth()->id()])
        );

        return new BarberServiceResoure($service);
    }

    public function show(string $id)
    {
        $userId = auth()->id();
        return new BarberServiceResoure(
            $this->barberService->getByIdAndUser($id, $userId)
        );
    }

    public function update(BarberRequestService $request, string $id)
    {
        $validated = $request->validated();

        $service = $this->barberService->update($id, $validated, auth()->id());

        return new BarberServiceResoure($service);
    }

    public function destroy(string $id)
    {
        $this->barberService->delete($id, auth()->id());
        return response()->noContent();
    }
}
