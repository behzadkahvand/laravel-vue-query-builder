<?php

namespace App\Services\Search\Operators;

abstract class BaseOperator
{
    public function supports(string $queryParam): bool
    {
        return str_starts_with($queryParam, $this->getSymbol()->name);
    }
}
