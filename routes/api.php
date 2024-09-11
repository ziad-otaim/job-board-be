<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\AdminController;

// Candidate Auth
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Logout
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);

    // Admin User Management
    Route::get('admin/users', [AdminController::class, 'getUsers']);
    Route::put('admin/users/{id}', [AdminController::class, 'updateUser']);
    Route::delete('admin/users/{id}', [AdminController::class, 'deleteUser']);

    // Admin Job Management
    Route::get('admin/jobs', [AdminController::class, 'getJobs']);
    Route::post('admin/jobs/{job}/approve', [AdminController::class, 'approveJob']);
    Route::delete('admin/jobs/{id}', [AdminController::class, 'deleteJob']);

    // Jobs (Non-admin endpoints)
    Route::get('jobs', [JobController::class, 'index']);
    Route::post('jobs', [JobController::class, 'store']);
    Route::get('jobs/{job}', [JobController::class, 'show']);
    Route::put('jobs/{job}', [JobController::class, 'update']);
    Route::delete('jobs/{job}', [JobController::class, 'destroy']);

    // Applications
    Route::post('jobs/{job}/apply', [ApplicationController::class, 'store']);
    Route::delete('jobs/{job}/application', [ApplicationController::class, 'destroy']);
    Route::get('applications', [ApplicationController::class, 'index']);
    Route::post('applications/{id}/accept', [ApplicationController::class, 'accept']);
    Route::post('applications/{id}/reject', [ApplicationController::class, 'reject']);

    // User Profile
    Route::get('/user/profile', [UserController::class, 'getProfile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
});
