<?php

namespace App\Services\Search\Operators;

use App\Enums\OperandCount;
use App\Enums\OperatorSymbol;
use App\Enums\OperatorType;

abstract class BaseEqualOperator extends BaseOperator implements OperatorInterface
{
    public function getSymbol(): OperatorSymbol
    {
        return OperatorSymbol::EQUAL;
    }

    public function getType(): OperatorType
    {
        return OperatorType::RELATIONAL;
    }

    public function getOperandCount(): OperandCount
    {
        return OperandCount::TWO;
    }

    public abstract function getEquivalentOperator(): string;
}
