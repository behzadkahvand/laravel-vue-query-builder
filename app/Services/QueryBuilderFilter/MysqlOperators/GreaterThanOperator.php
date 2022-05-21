<?php

namespace App\Services\QueryBuilderFilter\MysqlOperators;

use App\Services\Search\Operators\BaseGreaterThanOperator;

class GreaterThanOperator extends BaseGreaterThanOperator
{
    public function getEquivalentOperator(): string
    {
        return ">";
    }
}
