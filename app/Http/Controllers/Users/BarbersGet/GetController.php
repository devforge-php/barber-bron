<?php

namespace App\Http\Controllers\Users\BarbersGet;

use App\Http\Controllers\Controller;
use App\Http\Resources\BarberResource;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Cache kaliti
            $cacheKey = 'barbers_page_' . $request->page;

            // Barberlarni faqat kerakli maydonlari bilan olish
            $data = Cache::remember($cacheKey, now()->addMinutes(1), function () {
                return User::where('role', 'barber')
                    ->select(['id', 'image', 'username', 'name', 'lastname', 'phone', 'text_uz', 'text_ru', 'role'])
                    ->paginate(5);
            });

            // Resursga o'tkazish
            $resource = BarberResource::collection($data);

            return response()->json([
                'success' => true,
                'data' => $resource,
                'pagination' => [
                    'current_page' => $data->currentPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                    'last_page' => $data->lastPage(),
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Barber index error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Serverda xatolik yuz berdi',
            ], 500);
        }
    }
    public function show($id)
{
    try {
        // Cache kaliti
        $cacheKey = 'barber_' . $id;

        // Caching orqali ma'lumotni olish
        $barber = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($id) {
            return User::where('role', 'barber')
                ->select(['id', 'image', 'username', 'name', 'lastname', 'phone', 'text_uz', 'text_ru', 'role'])
                ->findOrFail($id);
        });

        // Resursga o'tkazish
        $resource = new BarberResource($barber);

        return response()->json([
            'success' => true,
            'data' => $resource,
        ], 200);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Barber topilmadi',
        ], 404);
    } catch (\Exception $e) {
        Log::error('Barber show error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Serverda xatolik yuz berdi',
        ], 500);
    }
}

}