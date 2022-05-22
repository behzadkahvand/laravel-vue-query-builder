<?php

namespace Tests\Unit\Services\BinaryTree;

use App\Enums\OperandCount;
use App\Enums\OperatorSymbol;
use App\Enums\OperatorType;
use App\Services\BinaryTree\TreeBuilderService;
use App\Services\QueryBuilderFilter\Exceptions\InvalidQueryParamData;
use App\Services\QueryBuilderFilter\Exceptions\UnsupportedSearchOperatorTypeException;
use App\Services\Search\Operators\OperatorInterface;
use App\Services\Utils\BinaryTree\BinaryTree;
use App\Services\Utils\Stack;
use Mockery;
use PHPUnit\Framework\TestCase;

class TreeBuilderServiceTest extends TestCase
{
    private Mockery\LegacyMockInterface|Mockery\MockInterface|Stack|null $stack;

    private ?TreeBuilderService $sut;

    private Mockery\LegacyMockInterface|OperatorInterface|Mockery\MockInterface|null $operatorMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->operatorMock = Mockery::mock(OperatorInterface::class);
        $this->stack        = Mockery::mock(Stack::class);

        $this->sut = new TreeBuilderService([$this->operatorMock, $this->operatorMock], $this->stack);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->operatorMock = null;
        $this->stack        = null;
        $this->sut          = null;
    }

    public function testItCanCreateTreeSuccessfully(): void
    {
        $param = 'NOT(GREATER_THAN(id,"fourth-post"))';

        $this->operatorMock->expects("supports")
                           ->with('NOT(GREATER_THAN(id,"fourth-post"))')
                           ->twice()
                           ->andReturn(true, false);

        $this->operatorMock->expects("supports")
                           ->with('GREATER_THAN(id,"fourth-post")')
                           ->andReturn(true);

        $this->operatorMock->expects("getSymbol")
                           ->withNoArgs()
                           ->twice()
                           ->andReturn(OperatorSymbol::NOT, OperatorSymbol::GREATER_THAN);

        $this->operatorMock->expects("getEquivalentOperator")
                           ->withNoArgs()
                           ->twice()
                           ->andReturn("NOT", "=");

        $this->operatorMock->expects("getOperandCount")
                           ->withNoArgs()
                           ->twice()
                           ->andReturn(OperandCount::ONE, OperandCount::TWO);

        $this->operatorMock->expects("getType")
                           ->withNoArgs()
                           ->twice()
                           ->andReturn(OperatorType::LOGICAL, OperatorType::RELATIONAL);

        $this->stack->expects("push")
                    ->with("(")
                    ->andReturn();

        $this->stack->expects("pop")
                    ->with()
                    ->andReturn("(");

        $this->stack->expects("isEmpty")
                    ->withNoArgs()
                    ->twice()
                    ->andReturn(true);

        $result = ($this->sut)($param);

        self::assertInstanceOf(BinaryTree::class, $result[0]);
        self::assertArrayHasKey("id_fourth_post", $result[1]);
        self::assertEquals("fourth-post", $result[1]["id_fourth_post"]);
    }

    public function testExceptionWhenOperatorNotValid(): void
    {
        $param = 'TEST(GREATER_THAN(id,"fourth-post"))';

        $this->operatorMock->expects("supports")
                           ->with('TEST(GREATER_THAN(id,"fourth-post"))')
                           ->twice()
                           ->andReturn(false);

        self::expectException(UnsupportedSearchOperatorTypeException::class);

        $result = ($this->sut)($param);
    }

    public function testExceptionWhenChildrenStructureNotValid(): void
    {
        $param = 'NOT(GREATER_THAN((id,"fourth-post"))';

        $this->operatorMock->expects("supports")
                           ->with('NOT(GREATER_THAN((id,"fourth-post"))')
                           ->andReturn(true, false);

        $this->operatorMock->expects("getSymbol")
                           ->withNoArgs()
                           ->andReturn(OperatorSymbol::NOT);

        $this->operatorMock->expects("getEquivalentOperator")
                           ->withNoArgs()
                           ->andReturn("NOT");

        $this->operatorMock->expects("getOperandCount")
                           ->withNoArgs()
                           ->andReturn(OperandCount::ONE);

        $this->operatorMock->expects("getType")
                           ->withNoArgs()
                           ->andReturn(OperatorType::LOGICAL);

        $this->stack->expects("push")
                    ->twice()
                    ->with("(")
                    ->andReturn();

        $this->stack->expects("pop")
                    ->with()
                    ->andReturn("(");

        $this->stack->expects("isEmpty")
                    ->withNoArgs()
                    ->twice()
                    ->andReturn(false);

        self::expectException(InvalidQueryParamData::class);

        ($this->sut)($param);
    }
}
