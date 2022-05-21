<?php

namespace App\Services\QueryBuilderFilter\Stages;

use App\Services\BinaryTree\TreeBuilderService;
use Closure;

class BuildTreeStage implements FilterStageInterface
{
    public function __construct(private TreeBuilderService $treeBuilderService)
    {
    }

    public function handle(QueryContextData $contextData, Closure $next)
    {
        if (!$contextData->hasQueryParam()) {
            return $next($contextData);
        }

        [$tree, $bingingParameters] = ($this->treeBuilderService)($contextData->getQueryParam());

        $contextData->setTree($tree)
                    ->setBindingParameters($bingingParameters);

        return $next($contextData);
    }
}
