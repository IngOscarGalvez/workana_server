<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\authentication\AuthenticationController;
use App\Http\Controllers\Api\v1\RoomController;

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

Route::prefix('/auth')->group(function () {
    Route::post('login', [AuthenticationController::class, 'login']);
    Route::post('register', [AuthenticationController::class, 'register']);
    Route::post('revokeToken', [AuthenticationController::class, 'revokeToken'])->middleware('auth:api');
});


Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('room', RoomController::class)->except(['update', 'destroy']);
    Route::post('joinMe', [RoomController::class, 'joinMe']);
    Route::post('giveVote', [RoomController::class, 'giveVote']);
    Route::get('UserRoomVote', [RoomController::class, 'UserRoomVote']);
});
