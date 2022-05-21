<?php

namespace App\Services\Search;

interface PaginatorInterface
{
    public function paginate($items, Pagination $pagination): iterable;
}
