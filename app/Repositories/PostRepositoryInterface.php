<?php

namespace App\Repositories;

interface PostRepositoryInterface
{
    public function createOrUpdate(array $conditions, array $data): void;

    public function updateById(string $id, array $data): void;

    public function deleteById(string $id): void;
}
