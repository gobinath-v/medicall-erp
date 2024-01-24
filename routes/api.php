<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExhibitorEventDashboardController;
use App\Http\Controllers\Api\MasterController;
use App\Http\Controllers\Api\PreviousEventController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\VisitorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::post('/login', [AuthController::class, 'login']);
Route::post('/otp-request', [AuthController::class, 'otpRequest']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::name('api.')
    ->middleware(['auth:sanctum', 'throttle:100,1'])
    ->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::post('/events/{event_id}/exhibitor/registration', [DashboardController::class, 'store']);
        Route::get('/exhibitor/profile', [ProfileController::class, 'show']);
        Route::post('/exhibitor/profile/edit', [ProfileController::class, 'updateProfile']);
        Route::post('/events/{event_id}/products', [ProfileController::class, 'updateEventProducts']);
        Route::post('/exhibitor/products/{product_id}', [ProfileController::class, 'storeProductImage']);
        Route::post('/exhibitor/products/{product_id}/images/{image_id}', [ProfileController::class, 'destroyProductImage']);
        Route::get('/events/{eventId}/appointments', [AppointmentController::class, 'showAppointments']);
        Route::post('/events/{eventId}/appointments/{appointmentId}', [AppointmentController::class, 'statusUpdate']);
        Route::get('/previous-events', [PreviousEventController::class, 'showPreviousEvents']);
        Route::get('/previous-events/{eventId}', [PreviousEventController::class, 'getPreviousEventCompletedAppointments']);
        Route::get('/products/{search}', [MasterController::class, 'showProducts']);
        Route::get('/categories/{search}', [MasterController::class, 'showCategories']);
        Route::post('/products/{product_name}', [MasterController::class, 'addProducts']);

        Route::get('/exhibitors/{eventId}/dashboard', [ExhibitorEventDashboardController::class, 'getEventDashboardData']);
    });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/visitors', [VisitorController::class, 'store']);
