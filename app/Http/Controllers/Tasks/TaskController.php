<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Auth::user()->tasks()->with('tags')->get();
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|integer|min:0|max:10',
            'type' => 'nullable|in:due_date,date_range,weekly,daily',
            'due_date' => 'nullable|date|after_or_equal:today|prohibited_unless:type,due_date',
            'start_date' => 'nullable|date|after_or_equal:today|before_or_equal:end_date|prohibited_unless:type,date_range',
            'end_date' => 'nullable|date|after_or_equal:today|after_or_equal:start_date|prohibited_unless:type,date_range',
            'days_of_week' => 'nullable|json|prohibited_unless:type,weekly',
            'time' => 'nullable|date_format:H:i',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);


        if($validator->fails()){
            return  response()->json(['Validation Error.', $validator->errors()]);    
        }

        $validatedData = $validator->validated();

        
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

    public function show($id)
    {
        $task = Auth::user()->tasks()->with('tags')->find($id);

        if (is_null($task)) {
            return response()->json('not found task', 404);;
        }
        return response()->json($task , 200);

    }

   
    public function update(Request $request, $id)
    {
        $task = Auth::user()->tasks()->find($id);

        if (is_null($task)) {
            return response()->json('not found task', 404);;
        }

        $validator =  Validator::make($request->all(),[
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'priority' => 'sometimes|integer|min:0|max:10',
            'type' => 'sometimes|in:due_date,date_range,weekly,daily',
            'due_date' => 'sometimes|date|after_or_equal:today',
            'start_date' => 'sometimes|date|after_or_equal:today|before_or_equal:end_date',
            'end_date' => 'sometimes|date|after_or_equal:today|after_or_equal:start_date',
            'days_of_week' => 'sometimes|json',
            'time' => 'sometimes|date_format:H:i',
            'tags' => 'sometimes|array',
            'tags.*' => 'exists:tags,id',
        ]);

        if($validator->fails()){
            return  response()->json(['Validation Error.', $validator->errors()]);    
        }
        
        $validatedData = $validator->validated();

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

    public function destroy($id)
    {
        $task = Auth::user()->tasks()->find($id);
        if (is_null($task)) {
            return response()->json('not found task', 404);;
        }
        $task->delete();
        return response()->json('sucsess', 200);
    }
}
