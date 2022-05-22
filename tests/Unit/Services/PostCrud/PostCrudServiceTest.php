<?php

namespace Tests\Unit\Services\PostCrud;

use App\DTO\PostData;
use App\Repositories\PostRepositoryInterface;
use App\Services\PostCrud\PostCrudService;
use Mockery;
use PHPUnit\Framework\TestCase;

class PostCrudServiceTest extends TestCase
{
    private PostRepositoryInterface|Mockery\LegacyMockInterface|Mockery\MockInterface|null $postRepository;

    private ?PostCrudService $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->postRepository = Mockery::mock(PostRepositoryInterface::class);
        $this->sut            = new PostCrudService($this->postRepository);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->postRepository = null;
        $this->sut            = null;
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testItCanStore(): void
    {
        $data = (new PostData())->setId(1)->setTitle("test");

        $this->postRepository->expects("createOrUpdate")
                             ->with(['id' => 1], ["id" => 1, "title" => "test"])
                             ->andReturn();

        $this->sut->store($data);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testItCanUpdate(): void
    {
        $data = (new PostData())->setTitle("test");

        $this->postRepository->expects("updateById")
                             ->with(1, ["title" => "test"])
                             ->andReturn();

        $this->sut->update(1, $data);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testItCanDelete(): void
    {
        $this->postRepository->expects("deleteById")
                             ->with(1)
                             ->andReturn();

        $this->sut->delete(1);
    }
}
