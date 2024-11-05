<?php

namespace App\Repositories;

use App\Models\Task;
use Carbon\Carbon;
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
        return $task->delete();
    }

    public function getTasksByPriority($priority)
    {
        return Auth::user()->tasks()->where('priority', $priority)->with('tags')->get();
    }

    public function getExpiredTasks(Carbon $date)
    {
        /*
            Если тип задачи — due_date, проверяем, если due_date меньше текущей даты.
            Если тип задачи — date_range, проверяем, если end_date меньше текущей даты.
            Если тип задачи — weekly или daily, задача не будет помечаться как проваленная, 
            так что эти типы нужно исключить из выборки.
        */
        
        return Task::where(function ($query) use ($date) {
            $query->where(function ($subQuery) use ($date) {
                $subQuery->where('type', 'due_date')
                         ->where('due_date', '<', $date);
            })->orWhere(function ($subQuery) use ($date) {
                $subQuery->where('type', 'date_range')
                         ->where('end_date', '<', $date);
            });
        })
        ->where('is_failed', false)
        ->where('is_completed', false)
        ->get();
    }
}