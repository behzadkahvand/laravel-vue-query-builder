<?php

namespace App\Services\Utils\BinaryTree;

class BinaryTree
{
    public function __construct(private BinaryNode $root)
    {
    }

    public function postOrderTraverse(?BinaryNode $node = null): iterable
    {
        $node = $node ?? $this->root;

        foreach ($node->getChildren() ?? [] as $child) {
            yield from $this->postOrderTraverse($child);
        }

        yield $node->getData();
    }
}
