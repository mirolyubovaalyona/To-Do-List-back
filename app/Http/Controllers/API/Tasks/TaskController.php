<?php

namespace App\Http\Controllers\API\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\StoreTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
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
        $tasks = Auth::user()->tasks()->with('tags')->cursorPaginate(10);
        return response()->json($tasks);
    }

    public function store(StoreTaskRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['type'] = $validatedData['type'] ?? 'due_date';
        
        if ($validatedData['type'] === 'due_date') {
            $validatedData['due_date'] = $validatedData['due_date'] ?? Carbon::today();
        } elseif ($validatedData['type'] === 'date_range') {
            $validatedData['start_date'] = $validatedData['start_date'] ?? Carbon::today();
            $validatedData['end_date'] = $validatedData['end_date'] ?? Carbon::today();
        }elseif  ($validatedData['type'] === 'weekly') {
            $validatedData['days_of_week'] = $validatedData['days_of_week'] ?? json_encode(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']);
        }
        
        $task = Auth::user()->tasks()->create($validatedData);
        
        if (isset($validatedData['tags'])) {
            $task->tags()->sync($validatedData['tags']);
        }

        return response()->json($task, 201);
    }

    public function show(Request $request, $task_id)
    {
        $task = $request->attributes->get('task');
        return response()->json($task , 200);

    }

   
    public function update(UpdateTaskRequest $request)
    {
        $validatedData = $request->validated();
        
        $task = $request->attributes->get('task');

        $validatedData['type'] = $validatedData['type'] ?? $task['type'];


        if ($validatedData['type'] === 'due_date') {
            $validatedData['due_date'] = $validatedData['due_date'] ?? Carbon::today();
            $validatedData['start_date'] = null;
            $validatedData['end_date'] = null;
            $validatedData['days_of_week'] = null;
        } elseif ($validatedData['type'] === 'date_range') {
            $validatedData['start_date'] = $validatedData['start_date'] ?? Carbon::today();
            $validatedData['end_date'] = $validatedData['end_date'] ?? Carbon::today();
            $validatedData['due_date'] = null;
            $validatedData['days_of_week'] = null;
        }elseif ($validatedData['type'] === 'weekly') {
            $validatedData['days_of_week'] = $validatedData['days_of_week'] ?? json_encode(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']);
            $validatedData['due_date'] = null;
            $validatedData['start_date'] = null;
            $validatedData['end_date'] = null;
        }

        $task->update($validatedData);

        if (isset($validatedData['tags'])) {
            $task->tags()->sync($validatedData['tags']);
        }
   
        return response()->json($task, 201);
    }

    public function destroy(Request $request, $task_id)
    {
        $task = $request->attributes->get('task');
        $task->delete();
        return response()->json('sucsess', 200);
    }

     // Получить все задачи с данным приоритетом 
    public function tasksByPriority($priority)
    {
        $tasks = Auth::user()->tasks()->where('priority', $priority)->with('tags')->get();

        if ($tasks->isEmpty()) {
            return response()->json(['error' => 'No tasks found with this priority'], 404);
        }

        return response()->json($tasks, 200);
    }
}
