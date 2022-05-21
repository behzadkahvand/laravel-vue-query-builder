<?php

namespace App\Services\PostCrud;

use App\DTO\PostData;
use App\Repositories\PostRepositoryInterface;

class PostCrudService
{
    public function __construct(private PostRepositoryInterface $postRepository)
    {
    }

    public function store(PostData $data): void
    {
        $this->postRepository->createOrUpdate(['id' => $data->getId()], $data->toArray());
    }

    public function update(string $id, PostData $data): void
    {
        $this->postRepository->updateById($id, $data->toArray());
    }

    public function delete(string $id): void
    {
        $this->postRepository->deleteById($id);
    }
}
