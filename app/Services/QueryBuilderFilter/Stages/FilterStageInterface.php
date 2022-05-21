<?php

namespace App\Services\QueryBuilderFilter\Stages;

use Closure;

interface FilterStageInterface
{
    public function handle(QueryContextData $contextData, Closure $next);
}
