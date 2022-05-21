<?php

namespace App\Services\QueryBuilderFilter\MysqlOperators;

use App\Services\Search\Operators\BaseAndOperator;

class AndOperator extends BaseAndOperator
{
    public function getEquivalentOperator(): string
    {
        return "AND";
    }
}
