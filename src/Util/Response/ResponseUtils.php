<?php

namespace App\Util\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

final class ResponseUtils
{
    public static function jsonOk(mixed $data): JsonResponse
    {
        return new JsonResponse(
            [
                'message' => 'ok',
                'status' => 200,
                'errors' => null,
                'data' => $data,
            ],
            200,
        );
    }

    public static function jsonCreated(mixed $data): JsonResponse
    {
        return new JsonResponse(
            [
                'message' => 'created',
                'status' => 201,
                'errors' => null,
                'data' => $data,
            ],
            201,
        );
    }

    public static function jsonBadRequest(array $errors): JsonResponse
    {
        return new JsonResponse(
            [
                'message' => 'bad request',
                'status' => 400,
                'errors' => $errors,
                'data' => null,
            ],
            400,
        );
    }

    public static function jsonServerInternalError(
        string $errorMessage,
    ): JsonResponse {
        return new JsonResponse(
            [
                'message' => $errorMessage,
                'status' => 500,
                'errors' => null,
                'data' => null,
            ],
            500,
        );
    }
}
