<?php

namespace App\Services\Search;

class SearchData
{
    public function __construct(private string $queryParameter, private string $sort)
    {
    }

    public function getQueryParameter(): string
    {
        return $this->queryParameter;
    }

    public function getSort(): string
    {
        return $this->sort;
    }
}
