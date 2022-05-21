<?php

namespace App\Services\Search\Drivers;

use App\Services\QueryBuilderFilter\QueryBuilderFilterService;
use App\Services\Search\AbstractSearchQuery;
use App\Services\Search\Pagination;
use App\Services\Search\PostSearchDriverInterface;
use App\Services\Search\QueryBuilderSearch;
use App\Services\Search\SearchData;

class QueryBuilderPostSearchDriver implements PostSearchDriverInterface
{
    public function __construct(private QueryBuilderFilterService $filterService)
    {
    }

    public function getQuery(SearchData $data, Pagination $pagination): AbstractSearchQuery
    {
        $queryBuilder = $this->filterService->filter("posts", [
            'query' => $data->getQueryParameter(),
            'sort'  => $data->getSort(),
        ]);

        return new QueryBuilderSearch($queryBuilder);
    }
}
