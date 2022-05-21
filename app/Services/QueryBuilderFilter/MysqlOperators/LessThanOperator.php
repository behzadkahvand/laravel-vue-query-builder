<?php

namespace App\Services\QueryBuilderFilter\MysqlOperators;

use App\Services\Search\Operators\BaseLessThanOperator;

class LessThanOperator extends BaseLessThanOperator
{
    public function getEquivalentOperator(): string
    {
        return "<";
    }
}
