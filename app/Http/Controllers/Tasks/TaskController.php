<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index()
    {
       
        Auth::user()->tasks;
        return  Auth::user()->tasks;
    }

    public function store(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'title' => 'required|string|max:255',
        ]);
        
        if($validator->fails()){
            return  response()->json(['Validation Error.', $validator->errors()]);    
        }

        $task = Auth::user()->tasks->create([
            $request->all()
        ]);

        $user = Auth::user();
        $Task = Task::create($request->all());
        
        return  response()->json($task, 201);
    }

    public function update(Request $request, Task $task)
    {
        // $this->authorize('update', $task);

        // $task->update($request->all());
        // return response()->json($task);
    }

    public function destroy(Task $task)
    {
        // $this->authorize('delete', $task);

        // $task->delete();
        // return response()->json(['message' => 'Task deleted']);
    }
}
