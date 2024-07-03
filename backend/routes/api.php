<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthenticatedSessionApiController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Controllers\Api\ProfileApiController;
use App\Http\Controllers\Api\TaskApiController;
use App\Http\Controllers\Api\ProjectApiController;

Route::prefix('v1')->group(function () {

    // Public routes
    Route::post('login', [AuthenticatedSessionApiController::class, 'store']);
    Route::post('register', [RegisteredUserController::class, 'store']);
        
    // Authenticated routes
    Route::middleware(['auth:sanctum', EnsureFrontendRequestsAreStateful::class])->group(function () {
        // Show all tasks
        Route::get('/tasks/all', [TaskApiController::class, 'index']);
        // Create task
        Route::post('/tasks/create', [TaskApiController::class, 'store']);
        // Show specific task
        Route::get('/tasks/show/{task}', [TaskApiController::class, 'show']);
        // Update a task
        Route::match(['put', 'patch'], '/tasks/{task}/update', [TaskApiController::class, 'update']);
        // Delete a task
        Route::delete('/tasks/{task}/delete', [TaskApiController::class, 'destroy']);
        // Custom route to fetch soft-deleted tasks
        Route::get('/tasks/trashed/temp', [TaskApiController::class, 'trashed']);
        // Restore a single deleted task
        Route::post('/tasks/trashed/temp/{task}/restore', [TaskApiController::class, 'restore']);
        // Restore all deleted tasks
        Route::post('/tasks/trashed/temp/restore-all', [TaskApiController::class, 'restoreAll']);
        // Get all tasks past their implementation date.
        Route::get('tasks/past-implementation', [TaskApiController::class, 'tasksPastImplementationDate']);


        // Route::apiResource('projects', ProjectApiController::class);
        // View all projects
        Route::get('/projects/all', [ProjectApiController::class, 'index']);
        // Create a project
        Route::post('/projects/create', [ProjectApiController::class, 'store']);
        // View a single project
        Route::get('/projects/show/{project}', [ProjectApiController::class, 'show']);
        // Route::match(['put', 'patch'], '/projects/{task}/update', [ProjectApiController::class, 'update']);
        // Route::delete('/projects/{task}/delete', [ProjectApiController::class, 'destroy']);
        // Route::get('/projects/trashed/temp', [ProjectApiController::class, 'trashed']);
        // Route::post('/projects/trashed/temp/{project}/restore', [ProjectApiController::class, 'restore']);
        // Route::post('/projects/trashed/temp/restore-all', [ProjectApiController::class, 'restoreAll']);

        Route::apiResource('profiles', ProfileApiController::class)->only(['index', 'edit', 'update', 'destroy']);
        Route::post('logout', [AuthenticatedSessionApiController::class, 'destroy']);
    });
});
