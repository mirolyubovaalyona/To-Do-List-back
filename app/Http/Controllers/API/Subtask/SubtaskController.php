<?php

namespace App\Http\Controllers\API\Subtask;

use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'content' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return  response()->json(['Validation Error.', $validator->errors()]);    
        }

        $validatedData = $validator->validated();
        $task = $request->attributes->get('task');
        $subtask = $task->subtasks()->create($validatedData);
        
        return response()->json($subtask, 201);
    }

    public function show(Request $request)
    {
        $subtask = $request->attributes->get('subtask');
        return response()->json($subtask , 200);
    }

   
    public function update(Request $request)
    {
        $subtask = $request->attributes->get('subtask');

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

    public function destroy(Request $request)
    {
        $subtask = $request->attributes->get('subtask');
        $subtask->delete();
        return response()->json('sucsess', 200);
    }
}
