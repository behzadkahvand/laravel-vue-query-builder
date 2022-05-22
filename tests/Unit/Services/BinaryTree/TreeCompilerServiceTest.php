<?php

namespace Tests\Unit\Services\BinaryTree;

use App\Services\BinaryTree\TreeCompilerService;
use App\Services\Utils\BinaryTree\BinaryNode;
use App\Services\Utils\BinaryTree\BinaryTree;
use App\Services\Utils\Stack;
use Mockery;
use PHPUnit\Framework\TestCase;

class TreeCompilerServiceTest extends TestCase
{
    private Mockery\LegacyMockInterface|Mockery\MockInterface|Stack|null $stack;

    private ?TreeCompilerService $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stack = Mockery::mock(Stack::class);

        $this->sut = new TreeCompilerService($this->stack);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->stack = null;
        $this->sut   = null;
    }

    public function testItCanCompileQueryFromTree(): void
    {
        $binaryTree = Mockery::mock(BinaryTree::class);

        $binaryNode = Mockery::mock(BinaryNode::class);

        $binaryTree->expects("postOrderTraverse")
                   ->withNoArgs()
                   ->andReturn([$binaryNode, $binaryNode, $binaryNode]);

        $binaryNode->expects("getValue")
                   ->withNoArgs()
                   ->times(3)
                   ->andReturn("1", "NOT", "=");

        $binaryNode->expects("isLeaf")
                   ->withNoArgs()
                   ->times(3)
                   ->andReturn(true, false, false);

        $binaryNode->expects("hasOneOperand")
                   ->withNoArgs()
                   ->twice()
                   ->andReturn(true, false);

        $this->stack->expects("pop")
                    ->withNoArgs()
                    ->times(4)
                    ->andReturn("( x = 1 )", "1", "x", "( NOT ( x = 1 ) )");

        $this->stack->expects("push")
                    ->with("1")
                    ->andReturn();

        $this->stack->expects("push")
                    ->with("( NOT ( x = 1 ) )")
                    ->andReturn();

        $this->stack->expects("push")
                    ->with("( x = 1 )")
                    ->andReturn();

        $result = ($this->sut)($binaryTree);

        self::assertEquals("( NOT ( x = 1 ) )", $result);
    }
}
