<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class TagRepository
{
    public function getAllTagsPaginated($perPage = 10)
    {
        return Auth::user()->tags()->cursorPaginate($perPage);
    }

    public function create(array $data)
    {
        return Auth::user()->tags()->create($data);
    }

    public function findById($tagId)
    {
        return Tag::findOrFail($tagId);
    }

    public function update(Tag $tag, array $data)
    {
        $tag->update($data);
        return $tag;
    }

    public function delete(Tag $tag)
    {
        return $tag->delete();
    }

    public function getTasksByTag(Tag $tag)
    {
        return $tag->tasks;
    }

}