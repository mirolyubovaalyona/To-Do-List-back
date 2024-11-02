<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use Illuminate\Support\Carbon;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getAllTasksPaginated($perPage = 10)
    {
        return $this->taskRepository->getAllWithTagsPaginated($perPage);
    }

    public function createTask(array $data)
    {
        $data['type'] = $data['type'] ?? 'due_date';

        if ($data['type'] === 'due_date') {
            $data['due_date'] = $data['due_date'] ?? Carbon::today();
        } elseif ($data['type'] === 'date_range') {
            $data['start_date'] = $data['start_date'] ?? Carbon::today();
            $data['end_date'] = $data['end_date'] ?? Carbon::today();
        } elseif ($data['type'] === 'weekly') {
            $data['days_of_week'] = $data['days_of_week'] ?? json_encode(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']);
        }

        $task = $this->taskRepository->create($data);

        if (isset($data['tags'])) {
            $task->tags()->sync($data['tags']);
        }

        return $task;
    }

    public function updateTask($task, array $data)
    {
        $data['type'] = $data['type'] ?? $task->type;

        if ($data['type'] === 'due_date') {
            $data['due_date'] = $data['due_date'] ?? Carbon::today();
            $data['start_date'] = null;
            $data['end_date'] = null;
            $data['days_of_week'] = null;
        } elseif ($data['type'] === 'date_range') {
            $data['start_date'] = $data['start_date'] ?? Carbon::today();
            $data['end_date'] = $data['end_date'] ?? Carbon::today();
            $data['due_date'] = null;
            $data['days_of_week'] = null;
        } elseif ($data['type'] === 'weekly') {
            $data['days_of_week'] = $data['days_of_week'] ?? json_encode(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']);
            $data['due_date'] = null;
            $data['start_date'] = null;
            $data['end_date'] = null;
        }

        $task = $this->taskRepository->update($task, $data);

        if (isset($data['tags'])) {
            $task->tags()->sync($data['tags']);
        }

        return $task;
    }
}