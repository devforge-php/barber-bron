<?php

namespace App\Http\Controllers\Admin\Orders;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Booking;
use App\Service\AdminOrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public $orderService;

    public function __construct(AdminOrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $orders = $this->orderService->get();
        return response()->json(OrderResource::collection($orders));
    }

    public function show(string $id)
    {
        $order = $this->orderService->findWithServices($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json(new OrderResource($order));
    }

    public function destroy(string $id)
    {
        // Bookingni topish
        $booking = Booking::find($id);
    
        if (!$booking) {
            return response()->json(['message' => 'Order not found'], 404);
        }
    
        // Bazadan o'chirish
        $booking->delete();
    
        // Cache-ni tozalash
        $this->orderService->forgetCaches($id);
    
        return response()->json(['message' => 'Order deleted successfully']);
    }
}
