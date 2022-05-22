<?php

namespace Tests\Unit\Services\Search;

use App\Services\Search\Pagination;
use App\Services\Search\Paginator;
use Illuminate\Database\Query\Builder;
use Mockery;
use PHPUnit\Framework\TestCase;

class PaginatorTest extends TestCase
{
    public function testItCanPaginate(): void
    {
        $pagination = new Pagination();

        $items = Mockery::mock(Builder::class);

        $items->expects("paginate")
              ->with(20)
              ->andReturn([]);

        $result = (new Paginator())->paginate($items, $pagination);

        self::assertIsArray($result);
    }
}
