<?php

namespace App\Services\Utils\BinaryTree;

class BinaryNode
{
    private array $children = [];

    public function __construct(private BinaryNodeDataInterface $data)
    {
    }

    public function getData(): BinaryNodeDataInterface
    {
        return $this->data;
    }

    public function addChild(BinaryNode $childNode): void
    {
        $this->children[] = $childNode;
    }

    public function getChildren(): array
    {
        return $this->children;
    }
}
