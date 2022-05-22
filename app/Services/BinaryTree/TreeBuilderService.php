<?php

namespace App\Services\BinaryTree;

use App\Enums\OperandCount;
use App\Enums\TreeNodeType;
use App\Services\QueryBuilderFilter\Exceptions\InvalidQueryParamData;
use App\Services\QueryBuilderFilter\Exceptions\UnsupportedSearchOperatorTypeException;
use App\Services\Search\Operators\OperatorInterface;
use App\Services\Utils\BinaryTree\BinaryNode;
use App\Services\Utils\BinaryTree\BinaryTree;
use App\Services\Utils\Stack;

class TreeBuilderService
{
    use TreeContextTrait;

    public function __construct(private iterable $operators, private Stack $stack)
    {
    }

    public function __invoke(string $queryParam): array
    {
        return [new BinaryTree($this->buildTreeRoot($queryParam)), $this->getBindingParameters()];
    }

    private function buildTreeRoot(string $queryParam): BinaryNode
    {
        $this->setTreeIsInvalid();

        /** @var OperatorInterface $operator */
        foreach ($this->operators as $operator) {
            if ($operator->supports($queryParam)) {
                $operandsParam = $this->findOperands($queryParam, $operator->getSymbol());

                $node = $this->createNode(
                    $operator->getEquivalentOperator(),
                    TreeNodeType::OPERATOR,
                    $operator->getOperandCount()
                );

                $this->setTreeIsValid();

                if ($this->isLastOperator($operator->getType())) {
                    $this->addLeafNodes($operandsParam, $node);

                    break;
                }

                foreach ($this->findChildren($operandsParam) as $param) {
                    $node->addChild($this->buildTreeRoot($param));
                }
            }
        }

        if (!$this->isTreeValid()) {
            throw new UnsupportedSearchOperatorTypeException("Operator not supported!");
        }

        return $node;
    }

    private function findChildren(string $queryParam): array
    {
        $children = [];

        for ($i = 0; $i < strlen($queryParam); $i++) {
            if ("(" == $queryParam[$i]) {
                $this->stack->push($queryParam[$i]);
            }

            if (")" == $queryParam[$i]) {
                $this->stack->pop();

                if ($this->stack->isEmpty()) {
                    $children[] = substr($queryParam, 0, $i + 1);
                    if (isset($queryParam[$i + 2])) {
                        $children[] = substr($queryParam, $i + 2);
                    }
                    break;
                }
            }
        }

        if (!$this->stack->isEmpty()){
            throw new InvalidQueryParamData("Query param data structure is invalid!");
        }

            return $children;
    }

    private function createNode(
        string        $value,
        TreeNodeType  $nodeType,
        ?OperandCount $operandCount = null
    ): BinaryNode {
        return new BinaryNode(new NodeData($value, $nodeType, $operandCount));
    }

    private function addLeafNodes(string $operandsParam, BinaryNode $node): void
    {
        [$variable, $value] = $this->getLeafNodes($operandsParam);

        $variable = sanitize_input($variable);
        $value    = sanitize_input($value);

        $bindingKey = $this->getBindingKey($variable, $value);

        $this->addBindingParameter($bindingKey, $value);

        $node->addChild($this->createNode($variable, TreeNodeType::OPERAND));
        $node->addChild($this->createNode(":$bindingKey", TreeNodeType::OPERAND));
    }
}
