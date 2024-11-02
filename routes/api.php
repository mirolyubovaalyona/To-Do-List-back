<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\Subtasks\SubtaskController;
use App\Http\Controllers\API\Tags\TagController;
use App\Http\Controllers\API\Tasks\TaskController;
use App\Http\Middleware\CheckSubtaskOwnership;
use App\Http\Middleware\CheckTagOwnership;
use App\Http\Middleware\CheckTaskOwnership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task_id}', [TaskController::class, 'show'])->middleware(CheckTaskOwnership::class);
    Route::patch('/tasks/{task_id}', [TaskController::class, 'update'])->middleware(CheckTaskOwnership::class);
    Route::delete('/tasks/{task_id}', [TaskController::class, 'destroy'])->middleware(CheckTaskOwnership::class);
    Route::get('/tasks/priority/{priority}', [TaskController::class, 'tasksByPriority']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks/{task_id}/subtasks', [SubtaskController::class, 'index'])->middleware(CheckTaskOwnership::class);
    Route::post('/tasks/{task_id}/subtasks', [SubtaskController::class, 'store'])->middleware(CheckTaskOwnership::class);
    Route::get('/tasks/{task_id}/subtasks/{subtask_id}', [SubtaskController::class, 'show'])->middleware([CheckTaskOwnership::class, CheckSubtaskOwnership::class]);
    Route::patch('/tasks/{task_id}/subtasks/{subtask_id}', [SubtaskController::class, 'update'])->middleware([CheckTaskOwnership::class, CheckSubtaskOwnership::class]);
    Route::delete('/tasks/{task_id}/subtasks/{subtask_id}', [SubtaskController::class, 'destroy'])->middleware([CheckTaskOwnership::class, CheckSubtaskOwnership::class]);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tags', [TagController::class, 'index']);
    Route::post('/tags', [TagController::class, 'store']);
    Route::get('/tags/{tag_id}', [TagController::class, 'show'])->middleware(CheckTagOwnership::class);
    Route::patch('/tags/{tag_id}', [TagController::class, 'update'])->middleware(CheckTagOwnership::class);
    Route::delete('/tags/{tag_id}', [TagController::class, 'destroy'])->middleware(CheckTagOwnership::class);
    Route::get('/tags/{tag_id}/tasks', [TagController::class, 'tasks'])->middleware(CheckTagOwnership::class);
});