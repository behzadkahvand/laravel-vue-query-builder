<?php

namespace App\Services\Search;

class SearchResult
{
    public function __construct(private iterable $results)
    {
    }

    public function getResults(): iterable
    {
        return $this->results;
    }
}
