<?php

namespace App\Http\Controllers\Users\BarberServicePost;

use App\Http\Controllers\Controller;
use App\Http\Resources\BarberServiceResoure;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostControlller extends Controller
{
      public function index(Request $request)
    {
        $userId = $request->query('user_id');

        // 1 so'rovga 1 minut cache berildi
        $services = Cache::remember("barber_services_{$userId}", 60, function () use ($userId) {
            return Service::where('user_id', $userId)->get();
        });

        return BarberServiceResoure::collection($services);
    }
 public function show(Request $request)
{
    try {
        $userId = $request->query('user_id');
        $serviceId = $request->query('service');

        if (!$userId || !$serviceId) {
            return response()->json([
                'success' => false,
                'message' => 'user_id va service parametrlari kerak.',
            ], 400);
        }

        $cacheKey = "barber_service_user_{$userId}_service_{$serviceId}";

        $service = Cache::remember($cacheKey, 60, function () use ($serviceId) {
            return Service::findOrFail($serviceId);
        });

        return new BarberServiceResoure($service);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Xizmat topilmadi.',
        ], 404);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Serverda xatolik yuz berdi.',
        ], 500);
    }
}


}
