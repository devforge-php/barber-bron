<?php

namespace App\Http\Controllers\Admin\Expenses;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ExpensesControlller extends Controller
{
   /**
     * Display a listing of the resource.
     */
  public function index(): JsonResponse
{
    $page = request()->get('page', 1); // Sahifa raqami
    $cacheKey = "expenses_page_{$page}"; // Har bir sahifaga alohida cache kaliti

    $expenses = Cache::remember($cacheKey, 60 * 5, function () {
        return Expense::latest()->paginate(10);
    });

    return response()->json($expenses);
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $expense = Expense::create($request->only(['name', 'price', 'date']));

        return response()->json([
            'message' => 'Xarajat muvaffaqiyatli yaratildi.',
            'data' => $expense
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return response()->json([
                'message' => 'Xarajat topilmadi'
            ], 404);
        }

        return response()->json($expense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return response()->json([
                'message' => 'Xarajat topilmadi'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'date' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $expense->update($request->only(['name', 'price', 'date']));

        return response()->json([
            'message' => 'Xarajat yangilandi',
            'data' => $expense
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return response()->json([
                'message' => 'Xarajat topilmadi'
            ], 404);
        }

        $expense->delete();

        return response()->json([
            'message' => 'Xarajat o\'chirildi'
        ]);
    }

    /**
     * Filter by date range.
     */
    public function filterByDateRange(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $expenses = Expense::whereBetween('date', [
            $request->input('start_date'),
            $request->input('end_date')
        ])->get();

        return response()->json($expenses);
    }

    /**
     * Get average price for a specific date.
     */
   
}
