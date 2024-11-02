<?php

namespace App\Http\Controllers\API\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\StoreTagRequest;
use App\Http\Requests\Tags\UpdateTagRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function index()
    {
        return Auth::user()->tags()->cursorPaginate(10);
    }

    public function store(StoreTagRequest $request)
    {
        $validatedData = $request->validated();
        
        $tag = Auth::user()->tags()->create($validatedData);
        

        return response()->json($tag, 201);
    }

    public function show(Request $request)
    {
        $tag = $request->attributes->get('tag');
        return response()->json($tag , 200);

    }

   
    public function update(UpdateTagRequest $request)
    {
        $validatedData = $request->validated();

        $tag = $request->attributes->get('tag');

        $tag->update($validatedData);
   
        return response()->json($tag, 201);
    }

    public function destroy(Request $request)
    {
        $tag = $request->attributes->get('tag');
        $tag->delete();
        return response()->json('sucsess', 200);
    }

    //вывод всех задач данного тега
    public function tasks(Request $request)
    {
        $tag = $request->attributes->get('tag');
        return response()->json($tag->tasks, 200);
    }
}
