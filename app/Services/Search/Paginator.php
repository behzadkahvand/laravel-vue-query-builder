<?php

namespace App\Services\Search;

use Illuminate\Database\Query\Builder;

class Paginator implements PaginatorInterface
{
    /**
     * @param Builder $items
     * @param Pagination $pagination
     *
     * @return iterable
     */
    public function paginate($items, Pagination $pagination): iterable
    {
        return $items->paginate($pagination->getLimit());
    }
}
