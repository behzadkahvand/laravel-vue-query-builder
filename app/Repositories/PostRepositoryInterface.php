<?php

namespace App\Repositories;

use App\DTO\PostData;

interface PostRepositoryInterface
{
    public function createOrUpdate(PostData $data): void;

    public function findById(string $id): PostData;

    public function updateById(string $id, PostData $data): void;

    public function deleteById(string $id): void;
}
