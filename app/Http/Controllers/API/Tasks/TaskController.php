<?php

namespace App\Http\Controllers\API\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\StoreTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Services\TaskService;

class TaskController extends Controller
{
    protected $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        $tasks = $this->taskService->getAllTasksPaginated(10);
        return response()->json($tasks);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->createTask($request->validated());
        return response()->json($task, 201);
    }

    public function show( $taskId)
    {
        $task = $this->taskService->findTaskById($taskId);
        return response()->json($task , 200);

    }

   
    public function update(UpdateTaskRequest $request, $taskId)
    {
        $task = $this->taskService->updateTask( $taskId, $request->validated());
        return response()->json($task, 201);
    }

    public function destroy($taskId)
    {
        $this->taskService->deleteTask( $taskId);
        return response()->json('sucsess', 200);
    }

     // Получить все задачи с данным приоритетом 
    public function tasksByPriority($priority)
    {
        $tasks = $this->taskService->getTasksByPriority($priority);

        if ($tasks->isEmpty()) {
            return response()->json(['error' => 'No tasks found with this priority'], 404);
        }

        return response()->json($tasks, 200);
    }
}
