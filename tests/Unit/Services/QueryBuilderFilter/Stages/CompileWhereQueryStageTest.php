<?php

namespace Tests\Unit\Services\QueryBuilderFilter\Stages;

use App\Services\BinaryTree\TreeCompilerService;
use App\Services\QueryBuilderFilter\Stages\CompileWhereQueryStage;
use App\Services\QueryBuilderFilter\Stages\QueryContextData;
use App\Services\Utils\BinaryTree\BinaryTree;
use Mockery;
use PHPUnit\Framework\TestCase;

class CompileWhereQueryStageTest extends TestCase
{
    private Mockery\LegacyMockInterface|TreeCompilerService|Mockery\MockInterface|null $treeCompilerService;

    private ?CompileWhereQueryStage $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->treeCompilerService = Mockery::mock(TreeCompilerService::class);
        $this->sut                 = new CompileWhereQueryStage($this->treeCompilerService);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->treeCompilerService = null;
        $this->sut                 = null;
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testItCanBuildTree(): void
    {
        $tree    = Mockery::mock(BinaryTree::class);
        $context = (new QueryContextData("posts", "EQUAL(x,1)", "-x"))
            ->setTree($tree);

        $this->treeCompilerService->expects("__invoke")
                                  ->with($tree)
                                  ->andReturn("query generated");

        $this->sut->handle($context, function () {
        });
    }
}
