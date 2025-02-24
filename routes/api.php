<?php

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ServiceController;
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

Route::post('reservation', [MailController::class, 'sendReservation']);
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::get('/services', [ServiceController::class, 'index']);
// Route::post('/crearAdmin', [UserController::class, 'crearAdministrador']);

Route::middleware('jwt')->group(function () {
  Route::get('user', [UserController::class, 'user']); // Ruta protegida por middleware
  Route::post('logout', [UserController::class, 'logout']);
  /*
  Route::post('/services', [ServiceController::class, 'store']);
  Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
  Route::patch('services')
  */
  // Route::apiResource('services', ServiceController::class);
  Route::middleware('admin')->group(function () {
    Route::get('/services/{limit}/{page}', [ServiceController::class, 'paginateServices']);
    Route::get('/services/{id}/{lang}', [ServiceController::class, 'show']);
    Route::post('/services', [ServiceController::class, 'store']);
    Route::post('/services/{id}', [ServiceController::class, 'update']);
    Route::post('/deleteService/{id}', [ServiceController::class, 'destroy']);

    Route::get('/drivers/{limit}/{page}', [DriverController::class, 'paginateConductores']);
    Route::post('/drivers', [DriverController::class, 'store']);
    Route::post('/drivers/{id}', [DriverController::class, 'update']);
    Route::post('/deleteDriver/{id}', [DriverController::class, 'destroy']);
  });
});
