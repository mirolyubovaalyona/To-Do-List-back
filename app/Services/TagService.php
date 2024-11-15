<?php

namespace App\Services;

use App\Repositories\TagRepository;

class TagService
{
    protected $taskRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function getAllTagsPaginated($perPage = 10)
    {
        return $this->tagRepository->getAllTagsPaginated($perPage);
    }

    public function createTag(array $data)
    {
        return $this->tagRepository->create($data);
    }
    public function findTagById($tagId)
    {
        return  $this->tagRepository->findById($tagId);
    }

    public function updateTag($tagId, array $data)
    {
        $tag = $this->tagRepository->findById($tagId);
        $tag = $this->tagRepository->update($tag, $data);

        return $tag;
    }

    public function deleteTag($tagId)
    {
        $tag = $this->tagRepository->findById($tagId);
        $this->tagRepository->delete($tag);
    }

    public function getTasksByTag($tagId)
    {
        $tag = $this->tagRepository->findById($tagId);
        return $this->tagRepository->getTasksByTag($tag);
    }
}