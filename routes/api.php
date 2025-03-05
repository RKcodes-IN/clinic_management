<?php

use App\Http\Controllers\InteraktCallbackController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UpdatedPatientDetailController;
use App\Models\UpdatedPatientDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/patient/store', [UpdatedPatientDetailController::class, 'updatePatientDetails']);
Route::post('/interkt/callback', [InteraktCallbackController::class, 'store']);
Route::get('/send/stock/alert', [StockController::class, 'sendAlert']);
