<?php

namespace App\Services\Search;

use Illuminate\Database\Query\Builder;

class QueryBuilderSearch extends AbstractSearchQuery
{
    public function __construct(private Builder $result)
    {
    }

    public function getResult(): iterable
    {
        return $this->result->get();
    }

    public function getResultQuery()
    {
        return $this->result;
    }
}
