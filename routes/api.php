<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\MeetingController;
use App\Http\Controllers\Api\FinancialController;
use App\Http\Controllers\Api\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Public routes (no auth required)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Products (admin, manager, employee can create/update)
    Route::middleware('role:admin,manager,employee')->group(function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
        Route::post('/products/{product}/stock', [ProductController::class, 'updateStock']);
    });

    // Categories (admin, manager can manage)
    Route::middleware('role:admin,manager')->group(function () {
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
    });

    // Orders
    Route::apiResource('orders', OrderController::class);
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']);
    Route::post('/orders/{order}/confirm', [OrderController::class, 'confirm']);
    Route::post('/orders/{order}/ship', [OrderController::class, 'ship']);
    Route::post('/orders/{order}/deliver', [OrderController::class, 'deliver']);

    // Users (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class);
    });

    // Dashboard/Stats (admin, manager)
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/dashboard/stats', [OrderController::class, 'stats']);
        Route::get('/dashboard/low-stock', [ProductController::class, 'lowStock']);
    });

    // Tasks
    Route::apiResource('tasks', TaskController::class);
    Route::post('/tasks/{task}/complete', [TaskController::class, 'complete']);
    Route::post('/tasks/{task}/assign', [TaskController::class, 'assign']);

    // Meetings
    Route::apiResource('meetings', MeetingController::class);
    Route::post('/meetings/{meeting}/attend', [MeetingController::class, 'markAttendance']);
    Route::post('/meetings/{meeting}/start', [MeetingController::class, 'start']);
    Route::post('/meetings/{meeting}/end', [MeetingController::class, 'end']);
    Route::get('/meetings/{meeting}/attendees', [MeetingController::class, 'attendees']);

    // Financial Management (Treasurer, President, Admin)
    Route::middleware('role:admin,president,treasurer')->group(function () {
        Route::apiResource('financial-transactions', FinancialController::class);
        Route::post('/financial-transactions/{transaction}/approve', [FinancialController::class, 'approve']);
        Route::get('/member-fees', [FinancialController::class, 'memberFees']);
        Route::post('/member-fees/{fee}/pay', [FinancialController::class, 'payFee']);
        Route::get('/financial-reports', [FinancialController::class, 'reports']);
    });

    // User Management (Admin, President)
    Route::middleware('role:admin,president')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::post('/users/{user}/activate', [UserController::class, 'activate']);
        Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate']);
    });

    // Dashboard and Reports
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/tasks-summary', [DashboardController::class, 'tasksSummary']);
    Route::get('/dashboard/upcoming-meetings', [DashboardController::class, 'upcomingMeetings']);
    Route::get('/dashboard/financial-summary', [DashboardController::class, 'financialSummary'])
        ->middleware('role:admin,president,treasurer');
});

Route::get('/test', function () {
    return response()->json([
        'message' => 'API funcionando correctamente',
        'timestamp' => now()
    ]);
});