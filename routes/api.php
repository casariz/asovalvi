<?php

use App\Http\Controllers\AssistantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ObligationController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout');
});

Route::controller(TaskController::class)->middleware('auth:sanctum')->group(function() {
    Route::get('/tasks', 'list');
    Route::post('/tasks', 'store');
    Route::get('/tasks/{task_id}', 'view');
    Route::put('/tasks/{task_id}/update', 'update');
    Route::put('/tasks/{task_id}/reject', 'reject');
    Route::put('/tasks/{task_id}/complete', 'complete');
    Route::get('/tasks/{task_id}/meeting', 'meeting_task');
});

Route::controller(MeetingController::class)->middleware('auth:sanctum')->group(function() {
    Route::get('/meetings', 'list');
    Route::post('/meetings', 'store');
    Route::get('/meetings/{meeting_id}', 'view');
    Route::put('/meetings/{meeting_id}/update', 'update');
    Route::put('/meetings/{meeting_id}/complete', 'complete');
});

Route::controller(ObligationController::class)->middleware('auth:sanctum')->group(function() {
    Route::get('/obligations', 'list');
    Route::post('/obligations', 'store');
    Route::get('/obligations/{obligation_id}', 'view');
    Route::put('/obligations/{obligation_id}/update', 'update');
    Route::put('/obligations/{obligation_id}/delete', 'delete');
    Route::get('/payments/{obligation_id}', 'listPayments');
    Route::post('/payments', 'storePayment');
});

Route::controller(StateController::class)->middleware('auth:sanctum')->group(function() {
    Route::get('/status/tasks', 'tasks');
    Route::get('/status/meetings', 'meetings');
    Route::get('/status/obligations', 'obligations');
    Route::get('/status/users', 'users');
});

Route::controller(TopicController::class)->middleware('auth:sanctum')->group(function() {
    Route::get('/topics', 'list');
    Route::post('/topics', 'store');
    Route::get('/topics/{meeting_id}', 'view');
    Route::put('/topics/{topic_id}/delete', 'delete');
});


Route::controller(AssistantController::class)->middleware('auth:sanctum')->group(function() {
    Route::get('/assistants', 'list');
    Route::post('/assistants', 'store');
    Route::post('/assistant_meetings', 'storeAssistants');
    Route::get('/assistants/{meeting_id}', 'view');
    Route::delete('/assistants/{meeting_id}/delete/{user_id}', 'delete');
});

Route::controller(UserController::class)->middleware('auth:sanctum')->group(function() {
    Route::get('/users', 'list');
    Route::get('/users/{id}', 'view');
    Route::put('/users/{id}/update', 'update');
    Route::put('/users/{id}/delete', 'delete');
    Route::get('/user_type', 'getUserType');
});