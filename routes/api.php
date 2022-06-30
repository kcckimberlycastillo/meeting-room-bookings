<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MeetingRoomController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::middleware([
    'web'
])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    });

    Route::get('booking-app/get-bookings', [BookingController::class, 'index']);
    
    //Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        
        Route::group(['prefix' => 'booking-app'], function () {
            Route::post('save', [BookingController::class, 'store']);
            Route::post('update/{id}', [BookingController::class, 'update']);
            Route::post('delete', [BookingController::class, 'softDelete']);
            Route::get('rooms', [MeetingRoomController::class, 'index']);
        });
    //});
});
