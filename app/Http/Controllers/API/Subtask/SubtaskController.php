<?php

namespace App\Http\Controllers\API\Subtask;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubtaskController extends Controller
{
    public function index($task_id)
    {
        $task = Auth::user()->tasks()->with('subtasks')->find($task_id);

        if (is_null($task)) {
            return response()->json(['error' => 'task not found'], 404);
        }

        return response()->json($task->subtasks, 200);
    }

    public function store(Request $request, $task_id)
    {
        $validator =  Validator::make($request->all(),[
            'content' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return  response()->json(['Validation Error.', $validator->errors()]);    
        }

        $validatedData = $validator->validated();
        $subtask = Auth::user()->tasks()->find($task_id)->subtasks()->create($validatedData);
        
        return response()->json($subtask, 201);
    }

    public function show($task_id, $subtask)
    {
        $task = Auth::user()->tasks()->find($task_id);
        if (is_null($task)) {
            return response()->json('not found task', 404);;
        }

        $subtask = $task->subtasks()->find($subtask);
        if (is_null($subtask)) {
            return response()->json('not found subtask', 404);;
        }

        return response()->json($subtask , 200);
    }

   
    public function update(Request $request, $task_id, $subtask)
    {
        $task = Auth::user()->tasks()->find($task_id);
        if (is_null($task)) {
            return response()->json('not found task', 404);;
        }

        $subtask = $task->subtasks()->find($subtask);
        if (is_null($subtask)) {
            return response()->json('not found subtask', 404);;
        }

        $validator =  Validator::make($request->all(),[
            'content' => 'sometimes|string|max:255',
            'is_completed' => 'sometimes|integer|min:0|max:1',
        ]);

        if($validator->fails()){
            return  response()->json(['Validation Error.', $validator->errors()]);    
        }
        
        $validatedData = $validator->validated();

        $subtask->update($validatedData);
   
        return response()->json($subtask, 201);
    }

    public function destroy($task_id, $subtask)
    {
        $task = Auth::user()->tasks()->find($task_id);
        if (is_null($task)) {
            return response()->json('not found task', 404);;
        }

        $subtask = $task->subtasks()->find($subtask);
        if (is_null($subtask)) {
            return response()->json('not found subtask', 404);;
        }

        $subtask->delete();
        return response()->json('sucsess', 200);
    }
}
