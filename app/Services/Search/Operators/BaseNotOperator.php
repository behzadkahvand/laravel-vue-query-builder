<?php

namespace App\Services\Search\Operators;

use App\Enums\OperandCount;
use App\Enums\OperatorSymbol;
use App\Enums\OperatorType;

abstract class BaseNotOperator extends BaseOperator implements OperatorInterface
{
    public function getSymbol(): OperatorSymbol
    {
        return OperatorSymbol::NOT;
    }

    public function getType(): OperatorType
    {
        return OperatorType::LOGICAL;
    }

    public function getOperandCount(): OperandCount
    {
        return OperandCount::ONE;
    }

    public abstract function getEquivalentOperator(): string;
}
