<?php

namespace App\Services\Search\Operators;

use App\Enums\OperandCount;
use App\Enums\OperatorSymbol;
use App\Enums\OperatorType;

interface OperatorInterface
{
    public function supports(string $queryParam): bool;

    public function getSymbol(): OperatorSymbol;

    public function getEquivalentOperator(): string;

    public function getType(): OperatorType;

    public function getOperandCount(): OperandCount;
}
