<?php

use App\Http\Controllers\Api\Dialer_memController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DialerCalleridController;
use App\Http\Controllers\Api\DialerQueuesController;
use App\Http\Controllers\Api\DialerAudioController;

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



Route::post('login', [RegisterController::class, 'login']);

Route::middleware('auth:sanctum')->get('/logout', function () {
    $user = auth()->user();
    $user->tokens()->delete(); 
    return response()->json([
        'status' => true,
        'message' => "User Successfully Logout",
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('register', [RegisterController::class, 'register']);

    // User Api 
    Route::get('user/details/{uid?}', [UserController::class, 'getuser']);
    Route::post('user/update/{uid}', [UserController::class, 'update']);
    Route::post('password-reset', [UserController::class, 'updatePassword']);
    Route::delete('user/delete/{uid}', [UserController::class, 'delete']);

    // Dialer Member
    Route::get('member/get/{uid?}', [Dialer_memController::class, 'index']);
    Route::post('member/store', [Dialer_memController::class, 'store']);
    Route::post('member/update/{id}', [Dialer_memController::class, 'update']);
    Route::delete('member/delete/{id}', [Dialer_memController::class, 'delete']);

    // Dialer Caller-id api
    Route::get('caller-id/get/{uid?}', [DialerCalleridController::class, 'index']);
    Route::post('caller-id/store', [DialerCalleridController::class, 'store']);
    Route::post('caller-id/update/{id}', [DialerCalleridController::class, 'update']);
    Route::delete('caller-id/delete/{id}', [DialerCalleridController::class, 'delete']);

    // Dialer queues api
    Route::get('queues/get/{uid?}', [DialerQueuesController::class, 'index']);
    Route::post('queues/store', [DialerQueuesController::class, 'store']);
    Route::post('queues/update/{name}', [DialerQueuesController::class, 'update']);
    Route::delete('queues/delete/{name}', [DialerQueuesController::class, 'delete']);
    Route::get('queues/count/{name}', [DialerQueuesController::class, 'count_member']);

    // Dialer audio api
    Route::get('audio/get/{uid?}', [DialerAudioController::class, 'index']);
    Route::post('audio/store', [DialerAudioController::class, 'store']);
    Route::post('audio/update/{id}', [DialerAudioController::class, 'update']);
    Route::delete('audio/delete/{id}', [DialerAudioController::class, 'delete']);
});
