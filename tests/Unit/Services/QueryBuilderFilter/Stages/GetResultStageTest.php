<?php

namespace Tests\Unit\Services\QueryBuilderFilter\Stages;

use App\Services\BinaryTree\TreeBuilderService;
use App\Services\QueryBuilderFilter\Stages\BuildTreeStage;
use App\Services\QueryBuilderFilter\Stages\GetResultStage;
use App\Services\QueryBuilderFilter\Stages\QueryContextData;
use App\Services\Utils\BinaryTree\BinaryTree;
use Illuminate\Database\Query\Builder;
use Mockery;
use PHPUnit\Framework\TestCase;

class GetResultStageTest extends TestCase
{
    public function testItCanCreateResult(): void
    {
        $context = (new QueryContextData("posts", "EQUAL(x,1)", "-x"))
            ->setWhereQuery("( x = 1 )")
            ->setSortQuery("y DESC")
            ->setBindingParameters(["x" => 1]);

        $sut = Mockery::mock(GetResultStage::class)->makePartial()->shouldAllowMockingProtectedMethods();

        $builder = Mockery::mock(Builder::class);

        $sut->expects("getInitQueryBuilder")
            ->with($context)
            ->andReturn($builder);

        $builder->expects("whereRaw")
            ->with("( x = 1 )",["x" => 1])
            ->andReturnSelf();

        $builder->expects("orderByRaw")
                ->with("y DESC")
                ->andReturnSelf();

        $result = $sut->handle($context, function () {
        });

        self::assertInstanceOf(Builder::class,$result);
    }
}
