<?php

namespace App\Services;

use App\Models\Subtask;
use App\Repositories\SubtaskRepository;
use App\Services\TaskService;

class SubtaskService
{
    protected $subtaskRepository;
    protected $taskService;

    public function __construct(SubtaskRepository $subtaskRepository, TaskService $taskService)
    {
        $this->subtaskRepository = $subtaskRepository;
        $this->taskService = $taskService;
    }

    public function getSubtasksByTaskId($taskId)
    {
        $task = $this->taskService->findTaskById($taskId);
        return $task->subtasks()->get();
    }

    public function createSubtask($taskId, array $data)
    {
        $task = $this->taskService->findTaskById($taskId);
        return $this->subtaskRepository->create($task, $data);
    }
    public function findSubtaskById($subtaskId)
    {
        return  $this->subtaskRepository->findById($subtaskId);
    }

    public function updateSubtask($subtaskId, array $data)
    {
        $subtask = $this->subtaskRepository->findById($subtaskId);
        return $this->subtaskRepository->update($subtask, $data);
    }

    public function deleteSubtask($subtaskId)
    {
        $subtask = $this->subtaskRepository->findById($subtaskId);
        return $this->subtaskRepository->delete($subtask);
    }

    public function subtaskCompleted($taskId, $subtaskId)
    {
        $subtask = $this->subtaskRepository->findById($subtaskId);
        if ($subtask) {
            $subtask->is_completed = true;
            $subtask->save();
            if ($this->areAllSubtasksCompleted($taskId)) {
                $this->taskService->taskCompleted($taskId);
            }
        }
        return  $subtask;
    }

    public function areAllSubtasksCompleted($taskId)
    {
        $task = $this->taskService->findTaskById($taskId);
        $subtasks = $task->subtasks()->get();
        return $subtasks->every(fn($subtask) => $subtask->is_completed);
    }

    public function markAllSubtasksAsCompleted($taskId)
    {
        $task = $this->taskService->findTaskById($taskId);
        $subtasks = $task->subtasks()->get();
        foreach ($subtasks as $subtask) {
            $subtask->is_completed = true;
            $subtask->save();
        }
    }
}