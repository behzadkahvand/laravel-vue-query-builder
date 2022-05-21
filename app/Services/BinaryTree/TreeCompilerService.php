<?php

namespace App\Services\BinaryTree;

use App\Services\Utils\BinaryTree\BinaryTree;
use App\Services\Utils\Stack;

class TreeCompilerService
{
    public function __construct(private Stack $stack)
    {
    }

    public function __invoke(BinaryTree $tree): string
    {
        /** @var NodeData $node */
        foreach ($tree->postOrderTraverse() as $node) {
            $stackItem = $node->getValue();

            if (!$node->isLeaf()) {
                if ($node->hasOneOperand()) {
                    $stackItem = "( " . $stackItem . " " . $this->stack->pop() . " )";
                } else {
                    $firstOperand  = $this->stack->pop();
                    $secondOperand = $this->stack->pop();
                    $stackItem     = "( " . $secondOperand . " " . $stackItem . " " . $firstOperand . " )";
                }
            }

            $this->stack->push($stackItem);
        }

        return $this->stack->pop();
    }
}
