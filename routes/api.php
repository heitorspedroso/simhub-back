<?php

use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Admin\BootController;
use App\Http\Controllers\Admin\PanelController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceTypeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\Client\ContactController;

Route::prefix('auth')->group(function () {
    Route::post('login',        [AuthController::class, 'login']);
    Route::post('logout',       [AuthController::class, 'logout'])->middleware('auth:sanctum'); //->middleware('auth:');
    Route::post('recover',      [AuthController::class, 'recover']);
});

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('/',                   [UserController::class, 'all']);
    Route::get('/client',             [UserController::class, 'clientAll']);
    Route::get('exists/{user}',       [UserController::class, 'exists']);
    Route::get('/me',                 [UserController::class, 'me']);
    Route::get('{id}',                [UserController::class, 'getPut']);
    Route::post('',                   [UserController::class, 'store']);
    Route::put('',                    [UserController::class, 'update']);
    Route::delete('{id}',             [UserController::class, 'destroy']);
});


Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::get('/boot',         [BootController::class, 'boot']);
    Route::get('/panel',        [PanelController::class, 'panel']);
});

Route::prefix('client')->middleware('auth:sanctum')->group(function () {
    Route::get('',              [ClientController::class, 'index']);
    Route::get('{id}',          [ClientController::class, 'show']);
    Route::post('',             [ClientController::class, 'store']);
    Route::put('',              [ClientController::class, 'update']);
    // Route::delete('{id}',       [ClientController::class, 'destroy']);
});

Route::prefix('device')->middleware('auth:sanctum')->group(function () {
    Route::get('',              [DeviceController::class, 'index']);
    Route::get('activesByEqp',  [DeviceController::class, 'activesByEqp']);
    Route::get('chart/{id}',    [DeviceController::class, 'showChart']);
    Route::get('{id}/detail',   [DeviceController::class, 'getDetail']);
    Route::get('{id}',          [DeviceController::class, 'getPut']);
    Route::post('',             [DeviceController::class, 'store']);
    Route::put('',              [DeviceController::class, 'update']);
    Route::delete('{id}',       [DeviceController::class, 'destroy']);
});
Route::prefix('device-type')->middleware('auth:sanctum')->group(function () {
    Route::get('',              [DeviceTypeController::class, 'index']);
    Route::post('',             [DeviceTypeController::class, 'store']);
    Route::delete('{id}',       [DeviceTypeController::class, 'destroy']);
});
Route::prefix('order')->middleware('auth:sanctum')->group(function () {
    Route::get('',          [OrderController::class, 'index']);
    Route::post('',         [OrderController::class, 'store']);
    Route::delete('{id}',   [OrderController::class, 'destroy']);
});
Route::prefix('alert')->middleware('auth:sanctum')->group(function () {
    Route::get('',              [AlertController::class, 'index']);
    Route::get('{id}',          [AlertController::class, 'show']);
});

Route::prefix('activity')->middleware('auth:sanctum')->group(function () {
    Route::get('',              [ActivityController::class, 'index']);
    // Route::post('',             [ActivityController::class, 'store']);
    // Route::delete('{id}',       [ActivityController::class, 'destroy']);
});

Route::prefix('contact')->middleware('auth:sanctum')->group(function () {
    Route::get('',              [ContactController::class, 'index']);
    Route::get('{id}',          [ContactController::class, 'show']);
    Route::post('',             [ContactController::class, 'store']);
    Route::put('',              [ContactController::class, 'update']);
    Route::delete('{id}',       [ContactController::class, 'destroy']);
});

Route::prefix('permission')->middleware('auth:sanctum')->group(function () {
    Route::get('/user-client',          [PermissionController::class, 'getUserClientFront']);
    Route::get('/user',                 [PermissionController::class, 'getUserAll']);
    Route::get('/client',               [PermissionController::class, 'getClientAll']);
    Route::post('/client',              [PermissionController::class, 'setClient']);
    Route::delete('/client',            [PermissionController::class, 'removeClient']);
    Route::get('/device-simple',        [PermissionController::class, 'getDeviceAllSimple']);
    Route::get('/device-client/{id}',   [PermissionController::class, 'getDeviceClient']);
    Route::get('/user-device',          [PermissionController::class, 'getUserDeviceAll']);
    Route::post('/device',              [PermissionController::class, 'setDevice']);
    Route::delete('/device',            [PermissionController::class, 'removeDevice']);
});


// Route::prefix('test')->middleware('auth:sanctum')->group(function() {
//     Route::get('',                      [TestController::class, 'index']);
//     Route::get('test',                  [TestController::class, 'test']);
// });

Route::prefix('test')->group(function () {
    Route::get('', function () {
        return ['teste-get', 'testando'];
    });
    Route::post('', function () {
        return ['teste-post', 'testando'];
    });
});



// Route:apiResource('user', Controller)
//     ->only(['get', 'store', 'update'])
//     ->middleware('auth:sanctum');
