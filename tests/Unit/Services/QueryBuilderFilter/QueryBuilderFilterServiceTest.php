<?php

namespace Tests\Unit\Services\QueryBuilderFilter;

use App\Services\QueryBuilderFilter\QueryBuilderFilterService;
use App\Services\QueryBuilderFilter\Stages\FilterStageInterface;
use App\Services\QueryBuilderFilter\Stages\QueryContextData;
use Illuminate\Database\Query\Builder;
use Illuminate\Pipeline\Pipeline;
use Mockery;
use PHPUnit\Framework\TestCase;

class QueryBuilderFilterServiceTest extends TestCase
{
    private Mockery\LegacyMockInterface|Mockery\MockInterface|Pipeline|null $pipeline;

    private ?array $stages;

    private ?QueryBuilderFilterService $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pipeline = Mockery::mock(Pipeline::class);
        $this->stages   = [Mockery::mock(FilterStageInterface::class)];

        $this->sut = new QueryBuilderFilterService($this->pipeline, $this->stages);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->pipeline = null;
        $this->stages   = null;
        $this->sut      = null;
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testItCanFilter(): void
    {
        $this->pipeline->expects("send")
            ->with(Mockery::type(QueryContextData::class))
            ->andReturnSelf();

        $this->pipeline->expects("through")
                       ->with($this->stages)
                       ->andReturnSelf();

        $this->pipeline->expects("thenReturn")
                       ->withNoArgs()
                       ->andReturn(Mockery::mock(Builder::class));

        $this->sut->filter("posts",["query"=>"test","sort"=>"test"]);
    }
}
