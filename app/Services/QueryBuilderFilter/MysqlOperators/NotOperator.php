<?php

namespace App\Services\QueryBuilderFilter\MysqlOperators;

use App\Services\Search\Operators\BaseNotOperator;

class NotOperator extends BaseNotOperator
{
    public function getEquivalentOperator(): string
    {
        return "NOT";
    }
}
