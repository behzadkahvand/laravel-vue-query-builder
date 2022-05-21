<?php

namespace App\Services\Utils;

use UnderflowException;

class Stack
{
    private array $stack = [];

    public function pop(): string
    {
        if ($this->isEmpty()) {
            throw new UnderflowException('Stack is empty');
        } else {
            return array_pop($this->stack);
        }
    }

    public function push(string $newItem): void
    {
        $this->stack[] = $newItem;
    }

    public function top(): string
    {
        return end($this->stack);
    }

    public function isEmpty(): bool
    {
        return empty($this->stack);
    }
}
