<?php

namespace App\Http\Controllers;

use App\DTO\PostData;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Services\PostCrud\PostCrudService;
use App\Services\Search\Exceptions\SearchDataValidationException;
use App\Services\Search\Pagination;
use App\Services\Search\PostSearchService;
use App\Services\Search\SearchData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PostController extends Controller
{
    public function index(Request $request, PostSearchService $postSearchService): JsonResponse
    {
        $data  = $request->query->all();
        $page  = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 20);

        try {
            $searchResult = $postSearchService->search(
                new SearchData(remove_white_spaces($data['query'] ?? ""), remove_white_spaces($data['sort'] ?? "")),
                new Pagination($page, $limit)
            );
        } catch (SearchDataValidationException $e) {
            return response($e->getMessage(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
                ->json();
        }

        return response()->json($searchResult->getResults());
    }

    public function store(StorePostRequest $request, PostCrudService $postCrudService): JsonResponse
    {
        $postCrudService->store(new PostData($request->post()));

        return response()->json([]);
    }

    public function update(string $id, UpdatePostRequest $request, PostCrudService $postCrudService): JsonResponse
    {
        $postCrudService->update($id, new PostData($request->post()));

        return response()->json([]);
    }

    public function delete(string $id, PostCrudService $postCrudService): JsonResponse
    {
        $postCrudService->delete($id);

        return response()->json([]);
    }
}
