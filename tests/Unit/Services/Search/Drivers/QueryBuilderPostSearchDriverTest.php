<?php

namespace Tests\Unit\Services\Search\Drivers;

use App\Services\QueryBuilderFilter\QueryBuilderFilterService;
use App\Services\Search\Drivers\QueryBuilderPostSearchDriver;
use App\Services\Search\Pagination;
use App\Services\Search\QueryBuilderSearch;
use App\Services\Search\SearchData;
use Illuminate\Database\Query\Builder;
use Mockery;
use PHPUnit\Framework\TestCase;

class QueryBuilderPostSearchDriverTest extends TestCase
{
    public function testItCanGetQuery(): void
    {
        $queryBuilderService = Mockery::mock(QueryBuilderFilterService::class);

        $queryBuilderService->expects("filter")
                            ->with("posts", ["query" => "test", "sort" => "sort"])
                            ->andReturn(Mockery::mock(Builder::class));

        $sut = (new QueryBuilderPostSearchDriver($queryBuilderService))
            ->getQuery(new SearchData("test", "sort"));

        self::assertInstanceOf(QueryBuilderSearch::class, $sut);
    }
}
