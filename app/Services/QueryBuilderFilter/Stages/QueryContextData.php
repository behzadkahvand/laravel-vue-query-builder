<?php

namespace App\Services\QueryBuilderFilter\Stages;

use App\Services\Utils\BinaryTree\BinaryTree;

final class QueryContextData
{
    use QueryContextTrait;

    private ?BinaryTree $tree = null;

    private string $whereQuery = "";

    private string $sortQuery = "";

    private array $bindingParameters = [];

    public function __construct(private string $sourceTable, private string $queryParam, private string $sortParam)
    {
    }

    public function getSourceTable(): string
    {
        return $this->sourceTable;
    }

    public function getQueryParam(): string
    {
        return $this->queryParam;
    }

    public function getSortParam(): string
    {
        return $this->sortParam;
    }

    public function getWhereQuery(): string
    {
        return $this->whereQuery;
    }

    public function setWhereQuery(string $whereQuery): self
    {
        $this->whereQuery = $whereQuery;

        return $this;
    }

    public function getSortQuery(): string
    {
        return $this->sortQuery;
    }

    public function setSortQuery(string $sortQuery): self
    {
        $this->sortQuery = $sortQuery;

        return $this;
    }

    public function getTree(): ?BinaryTree
    {
        return $this->tree;
    }

    public function setTree(?BinaryTree $tree): self
    {
        $this->tree = $tree;

        return $this;
    }

    public function getBindingParameters(): array
    {
        return $this->bindingParameters;
    }

    public function setBindingParameters(array $bindingParameters): self
    {
        $this->bindingParameters = $bindingParameters;

        return $this;
    }
}
