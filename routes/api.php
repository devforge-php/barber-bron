<?php

use App\Http\Controllers\Admin\Barbers\BarberControllelr;
use App\Http\Controllers\Admin\BarberWorksController;
use App\Http\Controllers\Admin\Bron\BronController;
use App\Http\Controllers\Admin\DayOffsBarber\BDayOffController;
use App\Http\Controllers\Admin\Expenses\ExpensesControlller;
use App\Http\Controllers\Admin\Notifactions\NotifactionController;
use App\Http\Controllers\Admin\NotifactionsArchiveComplited\NotiController;
use App\Http\Controllers\Admin\Orders\OrderController;
use App\Http\Controllers\Admin\ServiceBarber\BServiceController;

use App\Http\Controllers\Admin\Statiska\StatiskaControlller;
use App\Http\Controllers\Admin\UserCommets\CommetController;
use App\Http\Controllers\Admin\WorkTimeBarber\BWorkTimeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Barber\Commmets\CommetController as CommmetsCommetController;
use App\Http\Controllers\Barber\DayOff\DayOffController;
use App\Http\Controllers\Barber\Profile\ProfileController;
use App\Http\Controllers\Barber\Service\ServiceController;
use App\Http\Controllers\Barber\WorkTime\WorkTimeController;
use App\Http\Controllers\CometController;
use App\Http\Controllers\Users\BarbersDate\DateController;
use App\Http\Controllers\Users\BarberServicePost\PostControlller;
use App\Http\Controllers\Users\BarbersGet\GetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;










// Auth start
Route::post('login', [AuthController::class, 'login']);
// Auth End



Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {


    // Admin Panel start

    // Barber crud start
    Route::prefix('barbers')->group(function () {
        Route::get('/', [BarberControllelr::class, 'index']);       // Barberlar ro'yxati
        Route::post('/', [BarberControllelr::class, 'store']);      // Yangi barber yaratish
        Route::get('{id}', [BarberControllelr::class, 'show']);     // ID orqali ko'rsatish
        Route::post('{id}', [BarberControllelr::class, 'update']); // PATCH bilan yangilash
        Route::delete('{id}', [BarberControllelr::class, 'destroy']); // O'chirish
    });
    // Barber crud end

    // Admn Bron start
    Route::post('bronAdmin', [BronController::class, 'post']);
    // Admin Bron end

    // Order Start
    Route::resource('orders', OrderController::class);
    // Order End

    // Users Commet Start
    Route::resource('commetss', CommetController::class);
    Route::get('userscommets/{id}', [CommetController::class, 'showuserid']);
    // Users Commet End

    // Notifactions start
    Route::prefix('admin/notifications')->group(function () {
        Route::get('/', [NotifactionController::class, 'index']);
        Route::get('{id}', [NotifactionController::class, 'show']);
        Route::delete('{id}', [NotifactionController::class, 'destroy']);
    });
    Route::put('/notifications/{id}/complete', [NotifactionController::class, 'markAsCompleted']);
Route::delete('/notifications/{id}/archive', [NotifactionController::class, 'archiveNotification']);

    Route::get('/archive', [NotiController::class, 'archive']);
    Route::get('/completed', [NotiController::class, 'completed']);
    Route::get('/arcomapi', [NotiController::class, 'arcomapi']);

    Route::get('/notifications/search', [NotiController::class, 'search']);

    // Notifactions End

    // Service start
 Route::prefix('admin')->group(function () {
    Route::apiResource('services', BServiceController::class);
});
    // Service end

    // DafOffs start
    Route::prefix('day-offs')->group(function () {
    Route::get('/', [BDayOffController::class, 'index']);
    Route::post('/', [BDayOffController::class, 'store']);
    Route::put('/{id}', [BDayOffController::class, 'update']);
    Route::delete('/{id}', [BDayOffController::class, 'destroy']);
});
    // DafOffs End
    // Work Dayy Start
    Route::prefix('work-schedules')->group(function () {
    Route::get('/', [BWorkTimeController::class, 'index']);
    Route::post('/', [BWorkTimeController::class, 'store']);
    Route::put('/{id}', [BWorkTimeController::class, 'update']);
    Route::delete('/{id}', [BWorkTimeController::class, 'destroy']);
});
    // Work Dayy End

 Route::get('expenses/filter', [ExpensesControlller::class, 'filterByDateRange']);
Route::apiResource('expenses', ExpensesControlller::class);

Route::get('/admin/statistika/payments-date-range', [StatiskaControlller::class, 'getPaymentsByDateRange']);

// Qoshim Versiya
Route::get('barber-service/{id}', [BarberWorksController::class, 'service']);
Route::get('barber-worktime/{id}', [BarberWorksController::class, 'worktime']);
Route::get('barber-dayoff/{id}', [BarberWorksController::class, 'dayoff']);

    // Admin Panel end
Route::get('notifications-table', [BarberWorksController::class, 'barberserviceday']);


});


Route::middleware(['auth:sanctum', 'role:barber'])->group(function () {

    // Barber Start

// profile start

    Route::get('profile', [ProfileController::class, 'show']);
    Route::post('profile', [ProfileController::class, 'update']);
// profile end

// service start
    Route::apiResource('services', ServiceController::class);
// service end

// Work Time Start
 Route::apiResource('worktime', WorkTimeController::class);
// Work Time End

// DayOFF start
Route::apiResource('dayoff', DayOffController::class);
// DayOFF End

// Notifaction start
 Route::prefix('barber/notifications')->group(function () {
        Route::get('/', [NotifactionController::class, 'index']);
        Route::get('{id}', [NotifactionController::class, 'show']);
        Route::delete('{id}', [NotifactionController::class, 'destroy']);
        
    });
      Route::get('/archives', [NotiController::class, 'archive']);
    Route::get('/completeds', [NotiController::class, 'completed']);
// Notifaction End

// Commmet start
Route::apiResource('commets', CommmetsCommetController::class);
// Commmet End
    // Barber End

});



// Users Start

//  Barber Get
Route::get('barberss', [GetController::class, 'index']);
Route::get('barberss/{id}', [GetController::class, 'show']);

// Barber Serive get

Route::get('/service', [PostControlller::class, 'index']);
Route::get('/serviceId', [PostControlller::class, 'show']);


Route::get('/date/{user_id}', [DateController::class, 'getAvailableTimesForDate']);
    Route::post('bron', [BronController::class, 'post']);

Route::post('commet', [CometController::class, 'store']);
// Users End
