<?php

namespace App\Services\QueryBuilderFilter;

use App\Services\QueryBuilderFilter\Stages\QueryContextData;
use Illuminate\Database\Query\Builder;
use Illuminate\Pipeline\Pipeline;

class QueryBuilderFilterService
{
    public function __construct(private Pipeline $pipeline, private iterable $stages)
    {
    }

    public function filter(string $sourceTable, array $context): Builder
    {
        $queryContextData = new QueryContextData($sourceTable, $context['query'], $context['sort']);

        return $this->pipeline->send($queryContextData)
                                       ->through($this->stages)
                                       ->thenReturn();
    }
}
