<?php

declare(strict_types=1);

namespace Tyloo;

use Exception;
use JsonSerializable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponseHelpers
{
    public function respondNotFound(string|Exception $message, ?string $key = 'error'): JsonResponse
    {
        return $this->apiResponse([$key => $this->morphMessage($message)],Response::HTTP_NOT_FOUND);
    }

    public function respondWithSuccess(?array $contents = []): JsonResponse
    {
        return $this->apiResponse([] === $contents ? ['success' => true] : $contents);
    }

    public function respondOk(string $message): JsonResponse
    {
        return $this->respondWithSuccess(['success' => $message]);
    }

    public function respondUnAuthenticated(?string $message = null): JsonResponse
    {
        return $this->apiResponse( ['error' => $message ?? 'Unauthenticated'],Response::HTTP_UNAUTHORIZED);
    }

    public function respondForbidden(?string $message = null): JsonResponse
    {
        return $this->apiResponse(['error' => $message ?? 'Forbidden'],Response::HTTP_FORBIDDEN);
    }

    public function respondError(?string $message = null): JsonResponse
    {
        return $this->apiResponse(['error' => $message ?? 'Error'], Response::HTTP_BAD_REQUEST);
    }

    public function respondCreated(?array $data = []): JsonResponse
    {
        return $this->apiResponse($data,Response::HTTP_CREATED);
    }

    public function respondFailedValidation(string|Exception $message, ?string $key = 'message'): JsonResponse
    {
        return $this->apiResponse([$key => $this->morphMessage($message)],Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function respondNoContent(?array $data = []): JsonResponse
    {
        return $this->apiResponse($data, Response::HTTP_NO_CONTENT);
    }

    private function apiResponse(array $data, int $code = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse($data, $code);
    }

    private function morphMessage(string|Exception $message): string
    {
        return $message instanceof Exception ? $message->getMessage() : $message;
    }
}
