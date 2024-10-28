<?php

namespace App\Http\Controllers\API\Tags;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function index()
    {
        return  Auth::user()->tags;
    }

    public function store(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ]);


        if($validator->fails()){
            return  response()->json(['Validation Error.', $validator->errors()]);    
        }

        $validatedData = $validator->validated();
        
        $tag = Auth::user()->tags()->create($validatedData);
        

        return response()->json($tag, 201);
    }

    public function show($tag_id)
    {
        $tag = Auth::user()->tags()->find($tag_id);

        if (is_null($tag)) {
            return response()->json('not found tag', 404);;
        }
        return response()->json($tag , 200);

    }

   
    public function update(Request $request, $tag_id)
    {
        $tag = Auth::user()->tags()->find($tag_id);

        if (is_null($tag)) {
            return response()->json('not found tag', 404);;
        }

        $validator =  Validator::make($request->all(),[
            'name' => 'sometimes|string|max:255',
            'color' => ['sometimes', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ]);

        if($validator->fails()){
            return  response()->json(['Validation Error.', $validator->errors()]);    
        }
        
        $validatedData = $validator->validated();

        $tag->update($validatedData);
   
        return response()->json($tag, 201);
    }

    public function destroy($tag_id)
    {
        $tag = Auth::user()->tags()->find($tag_id);
        if (is_null($tag)) {
            return response()->json('not found task', 404);;
        }
        $tag->delete();
        return response()->json('sucsess', 200);
    }

    //вывод всех задач данного тега
    public function tasks($tag_id)
    {
        $tag = Auth::user()->tags()->with('tasks')->find($tag_id);

        if (is_null($tag)) {
            return response()->json(['error' => 'Tag not found'], 404);
        }

        return response()->json($tag->tasks, 200);
    }
}
