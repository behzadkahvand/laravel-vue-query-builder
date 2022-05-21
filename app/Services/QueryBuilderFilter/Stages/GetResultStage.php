<?php

namespace App\Services\QueryBuilderFilter\Stages;

use Closure;
use Illuminate\Support\Facades\DB;

class GetResultStage implements FilterStageInterface
{
    public function handle(QueryContextData $contextData, Closure $next)
    {
        $queryBuilder = DB::table($contextData->getSourceTable());

        if (!empty($contextData->getWhereQuery())) {
            $queryBuilder = $queryBuilder->whereRaw($contextData->getWhereQuery(),
                $contextData->getBindingParameters());
        }
        if (!empty($contextData->getSortQuery())) {
            $queryBuilder = $queryBuilder->orderByRaw($contextData->getSortQuery());
        }

        return $queryBuilder;
    }
}
