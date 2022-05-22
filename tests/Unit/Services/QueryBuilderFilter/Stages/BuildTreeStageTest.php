<?php

namespace Tests\Unit\Services\QueryBuilderFilter\Stages;

use App\Services\BinaryTree\TreeBuilderService;
use App\Services\QueryBuilderFilter\Stages\BuildTreeStage;
use App\Services\QueryBuilderFilter\Stages\QueryContextData;
use App\Services\Utils\BinaryTree\BinaryTree;
use Mockery;
use PHPUnit\Framework\TestCase;

class BuildTreeStageTest extends TestCase
{
    private Mockery\LegacyMockInterface|TreeBuilderService|Mockery\MockInterface|null $TreeBuilderService;

    private ?BuildTreeStage $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->TreeBuilderService = Mockery::mock(TreeBuilderService::class);
        $this->sut                = new BuildTreeStage($this->TreeBuilderService);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->TreeBuilderService = null;
        $this->sut                = null;
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testItCanBuildTree(): void
    {
        $context = new QueryContextData("posts", "EQUAL(x,1)", "-x");

        $this->TreeBuilderService->expects("__invoke")
                                 ->with("EQUAL(x,1)")
                                 ->andReturn([Mockery::mock(BinaryTree::class), []]);

        $this->sut->handle($context, function () {});
    }
}
