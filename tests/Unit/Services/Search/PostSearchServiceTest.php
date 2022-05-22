<?php

namespace Tests\Unit\Services\Search;

use App\Services\Search\AbstractSearchQuery;
use App\Services\Search\Pagination;
use App\Services\Search\PaginatorInterface;
use App\Services\Search\PostSearchDriverInterface;
use App\Services\Search\PostSearchService;
use App\Services\Search\SearchData;
use App\Services\Search\SearchResult;
use Illuminate\Database\Query\Builder;
use Mockery;
use PHPUnit\Framework\TestCase;

class PostSearchServiceTest extends TestCase
{
    public function testItCanSearch(): void
    {
        $postSearchDriver = Mockery::mock(PostSearchDriverInterface::class);
        $paginator        = Mockery::mock(PaginatorInterface::class);
        $searchData       = new SearchData("test param", "test sort");
        $pagination       = new Pagination();

        $searchQuery = Mockery::mock(AbstractSearchQuery::class);

        $postSearchDriver->expects("getQuery")
                         ->with($searchData)
                         ->andReturn($searchQuery);

        $resultQuery = Mockery::mock(Builder::class);

        $searchQuery->expects("getResultQuery")
                    ->withNoArgs()
                    ->andReturn($resultQuery);

        $paginator->expects("paginate")->with($resultQuery, $pagination)
                  ->andReturn([1, 2]);

        $result = (new PostSearchService($postSearchDriver, $paginator))
            ->search($searchData, $pagination);

        self::assertInstanceOf(SearchResult::class, $result);
    }
}
