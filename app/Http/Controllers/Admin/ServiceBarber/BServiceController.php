<?php

// App/Http/Controllers/Admin/ServiceBarber/BServiceController.php

namespace App\Http\Controllers\Admin\ServiceBarber;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\BarberServiceResoure;
use App\Models\Service;
use App\Service\ServiceService;

class BServiceController extends Controller
{
    public function __construct(protected ServiceService $serviceService) {}

   public function index()
{
    $services = $this->serviceService->getPaginated();
    return BarberServiceResoure::collection($services);
}

    public function store(ServiceRequest $request)
    {
        $service = $this->serviceService->store($request->validated());
        return new BarberServiceResoure($service);
    }

    public function show(Service $service)
    {
        return new BarberServiceResoure($service->load('barber'));
    }

    public function update(ServiceRequest $request, Service $service)
    {
        $updated = $this->serviceService->update($service, $request->validated());
        return new BarberServiceResoure($updated);
    }

    public function destroy(Service $service)
    {
        $this->serviceService->delete($service);
        return response()->json(['message' => 'Xizmat o\'chirildi']);
    }
}