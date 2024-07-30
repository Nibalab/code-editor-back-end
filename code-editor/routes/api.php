<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\OpenAIController;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/codes', [CodeController::class, 'store']);
    Route::get('/codes', [CodeController::class, 'index']);
    Route::get('/user-codes', [CodeController::class, 'loggedIn']);
    Route::get('/codes/{id}', [CodeController::class, 'show']);
    Route::put('/codes/{id}', [CodeController::class, 'update']);
    Route::delete('/codes/{id}', [CodeController::class, 'destroy']);

    Route::get('/user/search', [UserController::class, 'searchUsers']); // Ensure this route points to the correct method

    Route::get('/chat/history', [ChatController::class, 'getChatHistory']);
    Route::post('/chat/send', [ChatController::class, 'sendMessage']);
});

Route::post('/get-suggestions', [OpenAIController::class, 'getSuggestions']);