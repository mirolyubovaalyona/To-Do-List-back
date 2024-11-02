<?php

namespace App\Repositories;

use App\Models\Subtask;

class SubtaskRepository
{
    public function create($task, array $data)
    {
        return $task->subtasks()->create($data);
    }

    public function findById($subtaskId)
    {
        return Subtask::findOrFail($subtaskId);
    }

    public function update(Subtask $subtask, array $data)
    {
        return $subtask->update($data);
    }

    public function delete(Subtask $subtask)
    {
        return $subtask->delete();
    }
}