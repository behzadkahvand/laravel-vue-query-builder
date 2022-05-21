<?php

namespace App\Services\BinaryTree;

use App\Enums\OperatorSymbol;
use App\Enums\OperatorType;
use App\Services\QueryBuilderFilter\Exceptions\InvalidQueryParamData;

trait TreeContextTrait
{
    private bool $isValidTree = false;

    private array $bindingParameters = [];

    protected function findOperands(string $queryParam, OperatorSymbol $symbol): string
    {
        preg_match("/{$symbol->name}\((.*?)\)$/", $queryParam, $match);

        if (!isset($match[1])) {
            throw new InvalidQueryParamData("Query param data structure is invalid!");
        }

        return $match[1];
    }

    protected function isLastOperator(OperatorType $operatorType): bool
    {
        return OperatorType::RELATIONAL === $operatorType;
    }

    protected function getLeafNodes(string $queryParam): array
    {
        return explode(",", $queryParam);
    }

    protected function setTreeIsInvalid(): void
    {
        $this->isValidTree = false;
    }

    protected function setTreeIsValid(): void
    {
        $this->isValidTree = true;
    }

    protected function isTreeValid(): bool
    {
        return $this->isValidTree;
    }

    protected function getBindingKey(string $variable, $value): string
    {
        return $variable . "_" . str_replace("-", "_", $value);
    }

    protected function addBindingParameter(string $bindingKey, $parameter): void
    {
        $this->bindingParameters[$bindingKey] = $parameter;
    }

    protected function getBindingParameters(): array
    {
        return $this->bindingParameters;
    }
}
