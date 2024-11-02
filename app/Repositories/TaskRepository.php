<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskRepository
{
    public function getAllWithTagsPaginated($perPage = 10)
    {
        return Auth::user()->tasks()->with('tags')->cursorPaginate($perPage);
    }

    public function create(array $data)
    {
        return Auth::user()->tasks()->create($data);
    }

    public function findById($taskId)
    {
        return Task::with('tags')->findOrFail($taskId);
    }

    public function update(Task $task, array $data)
    {
        $task->update($data);
        return $task;
    }

    public function delete(Task $task)
    {
        $task->delete();
    }

    public function getTasksByPriority($priority)
    {
        return Auth::user()->tasks()->where('priority', $priority)->with('tags')->get();
    }
}