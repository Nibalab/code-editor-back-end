<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\OpenAIController;
use App\Http\Controllers\AssetController;


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

// Authentication routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Routes requiring authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('/user/search', [UserController::class, 'searchUsers']); // Ensure this route points to the correct method
});
    // Chat routes
    Route::get('/chat/history', [ChatController::class, 'getChatHistory']);
    Route::post('/chat/send', [ChatController::class, 'sendMessage']);
    
    // Admin routes
Route::get("/search",[UserController::class, "getUserBy"]);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admin', [AuthController::class, 'admin'])->can('access-admin');
    Route::get('users', [UserController::class, 'getAllUsers'])->can('access-admin');
    Route::get('users/{id}', [UserController::class, 'getUser'])->can('access-admin');
    Route::post('users', [UserController::class, 'createUser'])->can('access-admin');
    Route::put('users/{id}', [UserController::class, 'updateUser'])->can('access-admin');
    Route::delete('users/{id}', [UserController::class, 'deleteUser'])->can('access-admin');
});



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
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
    Route::post('/profile/{profileId}/upload-readme', [ProfileController::class, 'uploadReadme']);
    Route::get('/profile/readme', [ProfileController::class, 'getReadme']);

});

Route::post('/get-suggestions', [OpenAIController::class, 'getSuggestions']);
