<?php

namespace App\Services\Search;

abstract class AbstractSearchQuery
{
    abstract public function getResult(): iterable;

    abstract public function getResultQuery();
}
