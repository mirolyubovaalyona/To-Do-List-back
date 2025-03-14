<?php

namespace App\Http\Controllers\API\Subtasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subtasks\StoreSubtaskRequest;
use App\Http\Requests\Subtasks\UpdateSubtaskRequest;
use App\Services\SubtaskService;

class SubtaskController extends Controller
{
    protected $subtaskService;
    public function __construct(SubtaskService $subtaskService)
    {
        $this->subtaskService = $subtaskService;
    }
    public function index($taskId)
    {
        $subtasks = $this->subtaskService->getSubtasksByTaskId($taskId);
        return response()->json($subtasks, 200);
    }

    public function store(StoreSubtaskRequest $request, $taskId)
    {
        $subtask = $this->subtaskService->createSubtask($taskId, $request->validated());
        return response()->json($subtask, 201);
    }

    public function show($taskId, $subtaskId)
    {
        $subtask = $this->subtaskService->findSubtaskById( $subtaskId);
        return response()->json($subtask , 200);
    }

   
    public function update(UpdateSubtaskRequest $request, $taskId, $subtaskId)
    {
        $subtask = $this->subtaskService->updateSubtask($subtaskId, $request->validated());
        return response()->json($subtask, 201);
    }

    public function destroy($taskId, $subtaskId)
    {
        $this->subtaskService->deleteSubtask( $subtaskId);
        return response()->json('sucsess', 200);
    }

    public function subtaskСompletion($taskId, $subtaskId)
    {
        $subtask = $this->subtaskService->subtaskCompleted($taskId, $subtaskId);
        return response()->json($subtask , 201);
    }
}
