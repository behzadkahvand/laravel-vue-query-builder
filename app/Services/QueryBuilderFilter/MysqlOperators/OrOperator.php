<?php

namespace App\Services\QueryBuilderFilter\MysqlOperators;

use App\Services\Search\Operators\BaseOrOperator;

class OrOperator extends BaseOrOperator
{
    public function getEquivalentOperator(): string
    {
        return "OR";
    }
}
