<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\IpAddressController;
use App\Http\Controllers\Api\Dialer_memController;
use App\Http\Controllers\Api\DialerAudioController;
use App\Http\Controllers\Api\DialerQueuesController;
use App\Http\Controllers\Api\DialerRoutingController;
use App\Http\Controllers\Api\Queues_FilterController;
use App\Http\Controllers\Api\DialerCalleridController;

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
    Route::get('user/gets', [UserController::class, 'getallusers']);
    Route::post('user/update/{uid}', [UserController::class, 'update']);
    Route::post('password-reset', [UserController::class, 'updatePassword']);
    Route::delete('user/delete/{uid}', [UserController::class, 'delete']);

    // Dialer Member
    Route::get('member/get/{uid?}', [Dialer_memController::class, 'index']);
    Route::get('member/show/{id}', [Dialer_memController::class, 'show']);
    Route::post('member/store', [Dialer_memController::class, 'store']);
    Route::post('member/update/{id}', [Dialer_memController::class, 'update']);
    Route::delete('member/delete/{id}', [Dialer_memController::class, 'delete']);
    Route::get('sippeers/get/{uid?}', [Dialer_memController::class, 'sippeers_get']);
    Route::get('sippeers/show/{id}', [Dialer_memController::class, 'sippeers_show']);
    Route::get('member/status/{id}', [Dialer_memController::class, 'change_status']);

    // Dialer Caller-id api
    Route::get('caller-id/get/{uid?}', [DialerCalleridController::class, 'index']);
    Route::get('caller-id/show/{id}', [DialerCalleridController::class, 'show']);
    Route::post('caller-id/store', [DialerCalleridController::class, 'store']);
    Route::post('caller-id/update/{id}', [DialerCalleridController::class, 'update']);
    Route::delete('caller-id/delete/{id}', [DialerCalleridController::class, 'delete']);

    // Dialer queues api
    Route::get('queues/get/{uid?}', [DialerQueuesController::class, 'index']);
    Route::get('queues/show/{id}', [DialerQueuesController::class, 'show']);
    Route::post('queues/store', [DialerQueuesController::class, 'store']);
    Route::post('queues/update/{name}', [DialerQueuesController::class, 'update']);
    Route::delete('queues/delete/{name}', [DialerQueuesController::class, 'delete']);
    Route::get('queues/count/{name}', [DialerQueuesController::class, 'count_member']);

    // Dialer audio api
    Route::get('audio/get/{uid?}', [DialerAudioController::class, 'index']);
    Route::get('audio/show/{id}', [DialerAudioController::class, 'show']);
    Route::post('audio/store', [DialerAudioController::class, 'store']);
    Route::post('audio/update/{id}', [DialerAudioController::class, 'update']);
    Route::delete('audio/delete/{id}', [DialerAudioController::class, 'delete']);

    // Dialer queues_filter api
    Route::get('queues_filter/get/{uid?}', [Queues_FilterController::class, 'index']);
    Route::get('queues_filter/show/{id}', [Queues_FilterController::class, 'show']);
    Route::post('queues_filter/store', [Queues_FilterController::class, 'store']);
    Route::post('queues_filter/update/{id}', [Queues_FilterController::class, 'update']);
    Route::delete('queues_filter/delete/{id}', [Queues_FilterController::class, 'delete']);

    // Dialer routing api
    Route::get('routing/get/{uid?}', [DialerRoutingController::class, 'index']);
    Route::get('routing/show/{id}', [DialerRoutingController::class, 'show']);
    Route::post('routing/store', [DialerRoutingController::class, 'store']);
    Route::post('routing/update/{id}', [DialerRoutingController::class, 'update']);
    Route::post('routing/status_update/{id}', [DialerRoutingController::class, 'status_update']);
    Route::delete('routing/delete/{id}', [DialerRoutingController::class, 'delete']);

    // Dialer Agent Report api
    Route::get('cdr/report', [AgentController::class, 'get_class']);

    // Dialer ip_address api
    Route::get('ip_address/get/{uid?}', [IpAddressController::class, 'index']);
    Route::get('ip_address/show/{id}', [IpAddressController::class, 'show']);
    Route::post('ip_address/store', [IpAddressController::class, 'store']);
    Route::post('ip_address/update/{id}', [IpAddressController::class, 'update']);
    Route::delete('ip_address/delete/{id}', [IpAddressController::class, 'delete']);

    // Dashboard count api
    Route::get('count/get', [DashboardController::class, 'count']);
    Route::get('donut_chart/get', [DashboardController::class, 'donut_chart']);
    Route::get('group_list/get', [DashboardController::class, 'group_list']);
});