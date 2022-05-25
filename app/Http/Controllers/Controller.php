<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private array $metas = [];

    private string $message = 'Response successfully returned';

    private bool $hasErrors = false;


    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function hasErrors(): bool
    {
        return $this->hasErrors;
    }

    public function setHasErrors(bool $hasErrors): self
    {
        $this->hasErrors = $hasErrors;

        return $this;
    }

    protected function respond(
        $data = [],
        int $statusCode = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse {
        return response()->json(
            [
                'succeed' => !$this->hasErrors(),
                'message' => $this->getMessage(),
                'results' => $data,
            ],
            $statusCode,
            $headers
        );
    }

    protected function respondWithError(
        string $message = 'Error has been detected!',
        array  $constraints = [],
        int    $status = Response::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse {
        return $this->setHasErrors(true)
                    ->setMessage($message)
                    ->respond($constraints, $status);
    }

    protected function respondEntityRemoved(int $id): JsonResponse
    {
        return $this->setMessage('Entity has been removed successfully!')->respond(['id' => $id]);
    }

    public function respondNotTheRightParameters($message = 'Wrong parameter has been detected!'): JsonResponse
    {
        return $this->respondWithError($message, [], Response::HTTP_BAD_REQUEST);
    }

    public function respondInvalidQuery($message = 'Invalid query has been detected!'): JsonResponse
    {
        return $this->respondWithError($message, [], Response::HTTP_BAD_REQUEST);
    }

    public function respondInvalidParameters($message = 'Invalid parameters has been detected!'): JsonResponse
    {
        return $this->respondWithError($message, [], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function respondUnauthorized($message = 'Unauthorized action has been detected!'): JsonResponse
    {
        return $this->respondWithError($message, [], Response::HTTP_UNAUTHORIZED);
    }
}
