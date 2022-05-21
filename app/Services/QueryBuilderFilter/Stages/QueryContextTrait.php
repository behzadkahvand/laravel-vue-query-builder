<?php

namespace App\Services\QueryBuilderFilter\Stages;

trait QueryContextTrait
{
    public function hasQueryParam(): bool
    {
        return !empty($this->queryParam);
    }

    public function hasSortParam(): bool
    {
        return !empty($this->sortParam);
    }

    public function hasTree(): bool
    {
        return !is_null($this->tree);
    }
}
