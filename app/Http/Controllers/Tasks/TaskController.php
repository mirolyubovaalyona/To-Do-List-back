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
        return  Auth::user()->tasks;
    }

    public function create(Request $request, Task $task)
    {
        // create
    }

    public function store(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'name' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return  response()->json(['Validation Error.', $validator->errors()]);    
        }
        
        $task = Auth::user()->tasks()->create([
            'name' => $request->name,
        ]);
        

        return response()->json($task, 201);
    }

    public function show($id)
    {
        // create
    }

    public function edit($id)
    {
        // create
    }

    public function update(Request $request, Task $task)
    {
     
    }

    public function destroy($id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);
        $task->delete();
        return response()->json('sucsess', 200);
    }
}
