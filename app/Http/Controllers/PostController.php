<?php

namespace App\Http\Controllers;

use App\DTO\PostData;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Repositories\PostRepository;
use App\Services\Search\Exceptions\SearchDataValidationException;
use App\Services\Search\Pagination;
use App\Services\Search\PostSearchService;
use App\Services\Search\SearchData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request, PostSearchService $postSearchService): JsonResponse
    {
        $data  = $request->query->all();
        $page  = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 100);
        try {
            $searchResult = $postSearchService->search(
                new SearchData(trim($data['query'] ?? ""), remove_white_spaces($data['sort'] ?? "")),
                new Pagination($page, $limit)
            );
        } catch (SearchDataValidationException $e) {
            return $this->respondInvalidParameters($e->getMessage());
        }

        return $this->respond($searchResult->getResults());
    }

    public function view(string $id, PostRepository $postRepository): JsonResponse
    {
        return $this->respond($postRepository->findById($id)->toArray());
    }

    public function store(StorePostRequest $request, PostRepository $postRepository): JsonResponse
    {
        $postRepository->createOrUpdate(new PostData($request->post()));

        return $this->respond([]);
    }

    public function update(string $id, UpdatePostRequest $request, PostRepository $postRepository): JsonResponse
    {
        $postRepository->updateById($id, new PostData($request->post()));

        return $this->respond([]);
    }

    public function delete(string $id, PostRepository $postRepository): JsonResponse
    {
        $postRepository->deleteById($id);

        return $this->respond([]);
    }
}
