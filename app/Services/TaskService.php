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
    public function findTaskById($taskId)
    {
        return  $this->taskRepository->findById($taskId);
    }

    public function updateTask($taskId, array $data)
    {
        $task = $this->taskRepository->findById($taskId);

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

    public function deleteTask($taskId)
    {
        $task = $this->taskRepository->findById($taskId);
        $this->taskRepository->delete($task);
    }

    public function getTasksByPriority($priority)
    {
        return $this->taskRepository->getTasksByPriority($priority);
    }

    public function taskCompleted($taskId)
    {
        $task = $this->taskRepository->findById($taskId);
        if ($task) {
            $task->is_completed = true;
            $task->save();
            $subtasks = $task->subtasks()->get();
            foreach ($subtasks as $subtask) {
                $subtask->is_completed = true;
                $subtask->save();
            }
        }
        return  $task;
    }

     // Метод для проверки и обновления задач с истекшими сроками
     public function markExpiredTasksAsFailed()
     {
         // Получаем сегодняшнюю дату
         $today = Carbon::today();
 
         // Получаем задачи, срок которых истёк, но они еще не отмечены как проваленные
         $expiredTasks = $this->taskRepository->getExpiredTasks($today);
 
         // Отмечаем каждую задачу как проваленную
         foreach ($expiredTasks as $task) {
             $task->is_failed = true;
             $task->save();
         }
 
         // Возвращаем количество обновленных задач для логов
         return count($expiredTasks);
     }
}