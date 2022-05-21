<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostRepository extends BaseEloquentRepository implements PostRepositoryInterface
{
    protected function getModelClass()
    {
        return Post::class;
    }

    public function createOrUpdate(array $conditions, array $data): void
    {
        $this->getModel()::updateOrCreate($conditions, $data);
    }

    public function updateById(string $id, array $data): void
    {
        $post = $this->find([['id', '=', $id]]);

        if (!$post){
            throw (new ModelNotFoundException)->setModel($this->getModelClass(), $id);
        }

        foreach ($data as $column => $value) {
            $post->{$column} = $value;
        }

        $this->update($post);
    }

    public function deleteById(string $id): void
    {
        $result = $this->remove([['id', '=', $id]]);

        if (!$result) {
            throw (new ModelNotFoundException)->setModel($this->getModelClass(), $id);
        }
    }
}
