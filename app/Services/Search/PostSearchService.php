<?php

namespace App\Services\Search;

class PostSearchService
{
    public function __construct(
        private PostSearchDriverInterface $postSearchDriver,
        private PaginatorInterface        $paginator
    ) {
    }

    public function search(SearchData $data, Pagination $pagination): SearchResult
    {
        $searchQuery = $this->postSearchDriver->getQuery($data, $pagination);

        $result = $this->paginator->paginate($searchQuery->getResultQuery(), $pagination);

        return new SearchResult($result);
    }
}
