<?php

namespace App\Services\BinaryTree;

use App\Enums\OperandCount;
use App\Enums\TreeNodeType;
use App\Services\Utils\BinaryTree\BinaryNodeDataInterface;

class NodeData implements BinaryNodeDataInterface
{
    public function __construct(private string $value, private TreeNodeType $type, private ?OperandCount $operandCount)
    {
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getType(): TreeNodeType
    {
        return $this->type;
    }

    public function getOperandCount(): ?OperandCount
    {
        return $this->operandCount;
    }

    public function isLeaf(): bool
    {
        return TreeNodeType::OPERAND === $this->type;
    }

    public function hasOneOperand(): bool
    {
        return OperandCount::ONE === $this->operandCount;
    }
}
