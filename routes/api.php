<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Subtasks\SubtaskController;
use App\Http\Controllers\API\Tags\TagController;
use App\Http\Controllers\API\Tasks\TaskController;
use App\Http\Middleware\CheckSubtaskOwnership;
use App\Http\Middleware\CheckTagOwnership;
use App\Http\Middleware\CheckTaskOwnership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\API\Auth\VerifyEmailController;

// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//маршрут ссылки верификации имейла
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth:sanctum', 'signed', 'throttle:6,1'])
    ->name('verification.verify');
    
//маршрут для повторной отправки верификационного письма
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['message' => 'Verification email sent.']);
})->middleware(['auth:sanctum', 'throttle:6,1']);


Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // User Route
    Route::get('/user', fn(Request $request) => $request->user());

    // Task Routes
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index']);
        Route::post('/', [TaskController::class, 'store']);
        
        Route::prefix('{task_id}')->middleware(CheckTaskOwnership::class)->group(function () {
            Route::get('/', [TaskController::class, 'show']);
            Route::patch('/', [TaskController::class, 'update']);
            Route::delete('/', [TaskController::class, 'destroy']);
            Route::patch('/complete', [TaskController::class, 'taskСompletion']);

            // Subtask Routes
            Route::prefix('subtasks')->group(function () {
                Route::get('/', [SubtaskController::class, 'index']);
                Route::post('/', [SubtaskController::class, 'store']);
                
                Route::prefix('{subtask_id}')->middleware(CheckSubtaskOwnership::class)->group(function () {
                    Route::get('/', [SubtaskController::class, 'show']);
                    Route::patch('/', [SubtaskController::class, 'update']);
                    Route::delete('/', [SubtaskController::class, 'destroy']);
                    Route::patch('/complete', [SubtaskController::class, 'subtaskСompletion']);
                });
            });
        });

        Route::get('/priority/{priority}', [TaskController::class, 'tasksByPriority']);
    });

    // Tag Routes
    Route::prefix('tags')->group(function () {
        Route::get('/', [TagController::class, 'index']);
        Route::post('/', [TagController::class, 'store']);
        
        Route::prefix('{tag_id}')->middleware(CheckTagOwnership::class)->group(function () {
            Route::get('/', [TagController::class, 'show']);
            Route::patch('/', [TagController::class, 'update']);
            Route::delete('/', [TagController::class, 'destroy']);
            Route::get('/tasks', [TagController::class, 'tasks']);
        });
    });
});
