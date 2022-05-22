<?php

namespace App\Services\QueryBuilderFilter\Stages;

use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class GetResultStage implements FilterStageInterface
{
    public function handle(QueryContextData $contextData, Closure $next)
    {
        $queryBuilder = $this->getInitQueryBuilder($contextData);

        if (!empty($contextData->getWhereQuery())) {
            $queryBuilder = $queryBuilder->whereRaw($contextData->getWhereQuery(),
                $contextData->getBindingParameters());
        }
        if (!empty($contextData->getSortQuery())) {
            $queryBuilder = $queryBuilder->orderByRaw($contextData->getSortQuery());
        }

        return $queryBuilder;
    }

    protected function getInitQueryBuilder(QueryContextData $contextData): Builder
    {
        return DB::table($contextData->getSourceTable());
    }
}
