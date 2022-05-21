<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseEloquentRepository
{
    protected ?Model $model;

    public function __construct()
    {
        $this->makeModel();
    }

    abstract protected function getModelClass();

    private function makeModel()
    {
        $model = app($this->getModelClass());

        if (!$model instanceof Model) {
            throw new \Exception("Invalid Eloquent model");
        }

        $this->model = $model;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function getModelById(int $id): Model
    {
        return $this->getModel()->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->getModel()->create($data);
    }

    public function update(Model $model): bool
    {
        return $model->save();
    }

    public function removeByModel(Model $model): bool
    {
        return $model->delete();
    }

    public function removeById(int $id): bool
    {
        return ($this->getModel()->destroy($id) > 0);
    }

    public function remove(array $constraints): bool
    {
        $query = $this->getModel();

        foreach ($constraints as $where) {
            $query = $query->where($where[0], $where[1], $where[2]);
        }

        return $query->delete();
    }

    public function find(array $constraints, bool $findOne = true, array $relations = [], int $limit = 10): mixed
    {
        $query = $this->getModel();
        if (!empty($relations)) {
            $query = $query->with($relations);
        }
        foreach ($constraints as $where) {
            $query = $query->where($where[0], $where[1], $where[2]);
        }

        return ($findOne ? $query->first() : $this->getAll($limit, [], $query));
    }

    public function getAll(int $items = 10, array $columns = [], ?Builder $query = null): iterable
    {
        if ($query) {
            $modelQuery = $query;
        } else {
            $modelQuery = $this->getModel();
        }

        return $modelQuery->paginate(
            $items < 1 ? 10 : $items,
            count($columns) ? $columns : ['*']
        );
    }

    public function getAllWithOutPagination(array $columns = [], ?Builder $query = null): iterable
    {
        if ($query) {
            $modelQuery = $query;
        } else {
            $modelQuery = $this->getModel();
        }

        return $modelQuery->get(count($columns) ? $columns : ['*']);
    }

    public function countAll(): int
    {
        return $this->getModel()->count();
    }

    public function createBulk(array $data): void
    {
        $this->model::insert($data);
    }
}
