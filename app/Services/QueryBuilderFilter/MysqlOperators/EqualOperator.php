<?php

namespace App\Services\QueryBuilderFilter\MysqlOperators;

use App\Services\Search\Operators\BaseEqualOperator;

class EqualOperator extends BaseEqualOperator
{
    public function getEquivalentOperator(): string
    {
        return "=";
    }
}
