<?php

namespace App\Services\Search;

interface PostSearchDriverInterface
{
    public function getQuery(SearchData $data): AbstractSearchQuery;
}
