<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\authController;
use App\Http\Controllers\Api\blogController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\userController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\studentController;
use App\Http\Controllers\Api\NotificationController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::apiResource('blog', blogController::class);

Route::post('/register', [authController::class, 'register']);
Route::post('/login', [authController::class, 'login']);


Route::post('/student', [studentController::class, 'createStudent']);
Route::get('student', [studentController::class, 'getAllStudents']);
Route::get('student/{id}', [studentController::class, 'getStudent']);
Route::put('student/{id}', [studentController::class, 'updateStudent']);
Route::delete('student/{id}', [studentController::class, 'deleteStudent']);

Route::middleware('auth:sanctum')->group(function () {

 // Post Routes
       Route::apiResource('posts', PostController::class);

       // Comment Routes
       Route::apiResource('comments', CommentController::class);

    // user profile
    Route::get('/profile', [userController::class, 'profile']); // For authenticated user
    Route::get('/profile/{id}', [userController::class, 'profileById']);
    Route::middleware('auth:api')->put('/profile', [userController::class, 'updateProfile']);
Route::post('/logout', [authController::class, 'logout']);


//notifications
Route::get('/notifications', [NotificationController::class, 'index']);
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    
//Messages
Route::get('/messages', [MessageController::class, 'fetchAllConversations']);
    
    // Dynamic routes after
    Route::get('/messages/{receiverId}', [MessageController::class, 'fetchConversation']);
    Route::post('/messages', [MessageController::class, 'sendMessage']);
    Route::post('/messages/{senderId}/read', [MessageController::class, 'markAsRead']);

});

      