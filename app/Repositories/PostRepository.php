<?php

namespace App\Repositories;

use App\DTO\PostData;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostRepository extends BaseEloquentRepository implements PostRepositoryInterface
{
    protected function getModelClass()
    {
        return Post::class;
    }

    public function createOrUpdate(PostData $data): void
    {
        $this->getModel()::updateOrCreate(['id' => $data->getId()], $data->toArray());
    }

    public function findById(string $id): PostData
    {
        $post = $this->getPost($id);

        return (new PostData())->setId($post->id)
                               ->setTitle($post->title)
                               ->setContent($post->content)
                               ->setViews($post->views)
                               ->setTimestamp($post->timestamp);
    }

    public function updateById(string $id, PostData $data): void
    {
        $post = $this->getPost($id);

        foreach ($data->toArray() as $column => $value) {
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

    protected function getPost(string $id): mixed
    {
        $post = $this->find([['id', '=', $id]]);

        if (!$post) {
            throw (new ModelNotFoundException)->setModel($this->getModelClass(), $id);
        }

        return $post;
    }
}
