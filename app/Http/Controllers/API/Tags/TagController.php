<?php

namespace App\Http\Controllers\API\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\StoreTagRequest;
use App\Http\Requests\Tags\UpdateTagRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\TagService;

class TagController extends Controller
{
    protected $tagService;
    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }
    public function index()
    {
        $tags = $this->tagService->getAllTagsPaginated(10);
        return response()->json($tags);
    }

    public function store(StoreTagRequest $request)
    {
        $tag = $this->tagService->createTag($request->validated());
        return response()->json($tag, 201);
    }

    public function show($tagId)
    {
        $tag = $this->tagService->findTagById($tagId);
        return response()->json($tag , 200);

    }

    public function update(UpdateTagRequest $request, $tagId)
    {
        $tag = $this->tagService->updateTag($tagId, $request->validated());
        return response()->json($tag, 201);
    }

    public function destroy($tagId)
    {
        $this->tagService->deleteTag( $tagId);
        return response()->json('sucsess', 200);
    }

    //вывод всех задач данного тега
    public function tasks($tagId)
    {
        $tasks = $this->tagService->getTasksByTag($tagId);
        return response()->json($tasks, 200);
    }
}
