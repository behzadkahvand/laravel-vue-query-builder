<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSearchAndSortPostsSuccessfully(): void
    {
        $query = 'AND(OR(EQUAL(id,"first-post"),OR(AND(EQUAL(id,"second-post"),GREATER_THAN(views,100)),LESS_THAN(timestamp,1643157349))),NOT(EQUAL(id,"third-post")))';
        $sort  = 'views';

        $response = $this->getJson("/api/store/?query=$query&sort=$sort");

        $response->assertStatus(200)->assertJson(fn(AssertableJson $json) => $json->has('current_page')
                                                                                  ->has('first_page_url')
                                                                                  ->has("from")
                                                                                  ->has("last_page")
                                                                                  ->has("last_page_url")
                                                                                  ->has("path")
                                                                                  ->where('total', 2)
                                                                                  ->has('data', 2)
                                                                                  ->has('data.0',
                                                                                      fn($json) => $json->where('id',
                                                                                          "fifth-post")
                                                                                                        ->where('views',
                                                                                                            50)
                                                                                                        ->where('timestamp',
                                                                                                            1043157345)
                                                                                                        ->etc()
                                                                                  )
                                                                                  ->has('data.1',
                                                                                      fn($json) => $json->where('id',
                                                                                          "first-post")
                                                                                                        ->where('views',
                                                                                                            100)
                                                                                                        ->etc()
                                                                                  )
                                                                                  ->etc()
        );
    }

    public function testSearchFailedWhenQueryFormatIsNotValid(): void
    {
        $query = 'OR(EQUAL(id,"first-post")))),NOT((EQUAL(id,"third-post"))';
        $sort  = 'views';

        $response = $this->getJson("/api/store/?query=$query&sort=$sort");

        $response->assertStatus(500);
    }

    public function testSearchFailedWhenOperatorNotSupported(): void
    {
        $query = 'INVALIDOPERATOR(id,"first-post")';
        $sort  = '-views';

        $response = $this->getJson("/api/store/?query=$query&sort=$sort");

        $response->assertStatus(500);
    }

    public function testCreateNewPostSuccessfullyWhenIdNotExist(): void
    {
        $response = $this->postJson("/api/store/", [
                "id"        => "test",
                "title"     => "test title",
                "views"     => 10,
                "timestamp" => 1000,
            ]
        );
        $response->assertStatus(200);

        $this->assertDatabaseCount('posts', 6);
        $this->assertDatabaseHas('posts', [
            'id'        => 'test',
            'title'     => 'test title',
            'views'     => '10',
            'timestamp' => 1000,
            'content'   => null,
        ]);
    }

    public function testUpdatePostSuccessfullyWhenIdAlreadyExist(): void
    {
        $response = $this->patchJson("/api/store/", [
                "id"        => "first-post",
                "title"     => "first title",
                "views"     => 10,
                "timestamp" => 1000,
                "content" => "content test"
            ]
        );
        $response->assertStatus(200);

        $this->assertDatabaseCount('posts', 5);

        $this->assertDatabaseHas('posts', [
            'id'        => 'first-post',
            'title'     => 'first title',
            'views'     => 10,
            'timestamp' => 1000,
            "content" => "content test"
        ]);
    }

    public function testUpdateByIdSuccessfully(): void
    {
        $timestamp = time();
        $response = $this->putJson("/api/store/second-post", [
                "title"     => "second title",
                "views"     => 20,
                "timestamp" => $timestamp,
                "content" => "content second test"
            ]
        );
        $response->assertStatus(200);

        $this->assertDatabaseCount('posts', 5);

        $this->assertDatabaseHas('posts', [
            'id'        => 'second-post',
            'title'     => 'second title',
            'views'     => 20,
            'timestamp' => $timestamp,
            "content" => "content second test"
        ]);
    }

    public function testUpdateFailedWhenPostNotExist(): void
    {
        $response = $this->putJson("/api/store/test-post", [
                "title"     => "second title",
                "views"     => 20,
                "timestamp" => time(),
                "content" => "content second test"
            ]
        );
        $response->assertStatus(404);
    }

    public function testDeleteSuccessfully(): void
    {
        $response = $this->deleteJson("/api/store/second-post");

        $response->assertStatus(200);

        $this->assertDatabaseCount('posts', 4);
    }

    public function testDeleteFailedWhenPostNotExist(): void
    {
        $response = $this->deleteJson("/api/store/test-post");

        $response->assertStatus(404);
    }
}
