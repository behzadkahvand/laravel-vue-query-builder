<?php

namespace App\Services\QueryBuilderFilter\Stages;

use App\Services\BinaryTree\TreeCompilerService;
use Closure;

class CompileWhereQueryStage implements FilterStageInterface
{
    public function __construct(private TreeCompilerService $treeCompilerService)
    {
    }

    public function handle(QueryContextData $contextData, Closure $next)
    {
        if (!$contextData->hasTree()) {
            return $next($contextData);
        }

        $contextData->setWhereQuery(($this->treeCompilerService)($contextData->getTree()));

        return $next($contextData);
    }
}
