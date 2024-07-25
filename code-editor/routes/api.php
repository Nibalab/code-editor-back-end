<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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

Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [\App\Http\Controllers\AuthController::class, 'user']);
    Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admin', [AuthController::class, 'admin'])->can('access-admin');
    Route::get('users', [UserController::class, 'getAllUsers'])->can('access-admin');
    Route::get('users/{id}', [UserController::class, 'getUser'])->can('access-admin');
    Route::post('users', [UserController::class, 'createUser'])->can('access-admin');
    Route::put('users/{id}', [UserController::class, 'updateUser'])->can('access-admin');
    Route::delete('users/{id}', [UserController::class, 'deleteUser'])->can('access-admin');
});
