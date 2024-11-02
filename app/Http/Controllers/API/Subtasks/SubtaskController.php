<?php

namespace App\Http\Controllers\API\Subtasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subtasks\StoreSubtaskRequest;
use App\Http\Requests\Subtasks\UpdateSubtaskRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubtaskController extends Controller
{
    public function index(Request $request)
    {
        $task = $request->attributes->get('task');
        return response()->json($task->subtasks, 200);
    }

    public function store(StoreSubtaskRequest $request)
    {
        $validatedData = $request->validated();
        $task = $request->attributes->get('task');
        $subtask = $task->subtasks()->create($validatedData);
        
        return response()->json($subtask, 201);
    }

    public function show(Request $request)
    {
        $subtask = $request->attributes->get('subtask');
        return response()->json($subtask , 200);
    }

   
    public function update(UpdateSubtaskRequest $request)
    {
        $validatedData = $request->validated();

        $subtask = $request->attributes->get('subtask');

        $subtask->update($validatedData);
   
        return response()->json($subtask, 201);
    }

    public function destroy(Request $request)
    {
        $subtask = $request->attributes->get('subtask');
        $subtask->delete();
        return response()->json('sucsess', 200);
    }
}
