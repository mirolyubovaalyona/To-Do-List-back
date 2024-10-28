<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\Subtask\SubtaskController;
use App\Http\Controllers\API\Tags\TagController;
use App\Http\Controllers\API\Tasks\TaskController;
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
    Route::get('/tasks/{task_id}', [TaskController::class, 'show']);
    Route::patch('/tasks/{task_id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task_id}', [TaskController::class, 'destroy']);
    Route::get('/tasks/priority/{priority}', [TaskController::class, 'tasksByPriority']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks/{task_id}/subtasks', [SubtaskController::class, 'index']);
    Route::post('/tasks/{task_id}/subtasks', [SubtaskController::class, 'store']);
    Route::get('/tasks/{task_id}/subtasks/{subtask_id}', [SubtaskController::class, 'show']);
    Route::patch('/tasks/{task_id}/subtasks/{subtask_id}', [SubtaskController::class, 'update']);
    Route::delete('/tasks/{task_id}/subtasks/{subtask_id}', [SubtaskController::class, 'destroy']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tags', [TagController::class, 'index']);
    Route::post('/tags', [TagController::class, 'store']);
    Route::get('/tags/{tag_id}', [TagController::class, 'show']);
    Route::patch('/tags/{tag_id}', [TagController::class, 'update']);
    Route::delete('/tags/{tag_id}', [TagController::class, 'destroy']);
    Route::get('/tags/{tag_id}/tasks', [TagController::class, 'tasks']);
});