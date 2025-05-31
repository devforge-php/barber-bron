<?php

namespace App\Http\Controllers\Admin\Barbers;

use App\Http\Controllers\Controller;
use App\Http\Requests\BarberRequest;
use App\Http\Resources\BarberResource;
use App\Service\AdminBerBerService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Nette\Schema\ValidationException;

class BarberControllelr extends Controller
{
  protected $barberService;

    public function __construct(AdminBerBerService $barberService)
    {
        $this->barberService = $barberService;
    }

    public function index()
    {
        try {
            $barbers = $this->barberService->get();
            return response()->json(BarberResource::collection($barbers));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(BarberRequest $request)
    {
        try {
            $barber = $this->barberService->store($request->validated());
            return response()->json(new BarberResource($barber));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $barber = $this->barberService->show($id);
            return response()->json(new BarberResource($barber));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:6',
            'image' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $barber = $this->barberService->update($validator->validated(), $id);
            return response()->json(new BarberResource($barber));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server xatosi: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->barberService->delete($id);
            return response()->json(['message' => 'Barber o\'chirildi']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
