<?php

namespace App\Services\QueryBuilderFilter\Stages;

use Closure;

class SortQueryStage implements FilterStageInterface
{
    public function handle(QueryContextData $contextData, Closure $next)
    {
        if (!$contextData->hasSortParam()) {
            return $next($contextData);
        }

        $contextData->setSortQuery($this->getSortQuery($contextData->getSortParam()));

        return $next($contextData);
    }

    private function getSortQuery(string $sortParam): string
    {
        $firstCharacter = substr($sortParam, 0, 1);

        $isHyphened = "-" === $firstCharacter;

        $sortDirection = $isHyphened ? "DESC" : "ASC";
        $sortBy        = $isHyphened ? substr($sortParam, 1) : $sortParam;

        return $sortBy . " " . $sortDirection;
    }
}
