<?php

namespace App\Util\Response;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ResponseUtils
{
    /**
     * @public
     * @static
     *
     * @param mixed $data
     * @return Response
     */
    public static function noContent(): Response
    {
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @public
     * @static
     *
     * @param mixed $data
     * @return JsonResponse
     */
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

    /**
     * @public
     * @static
     *
     * @param mixed $data
     * @return JsonResponse
     */
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

    /**
     * @public
     * @static
     *
     * @param array{message: string, errors: ?array} $errorData
     * @return JsonResponse
     */
    public static function jsonBadRequest(array $errorData): JsonResponse
    {
        if (!array_key_exists('message', $errorData)) {
            $errorData['message'] = 'bad request';
        }

        if (!array_key_exists('errors', $errorData)) {
            $errorData['errors'] = null;
        }

        return new JsonResponse(
            [
                'message' => $errorData['message'],
                'status' => 400,
                'errors' => $errorData['errors'],
                'data' => null,
            ],
            400,
        );
    }

    /**
     * @public
     * @static
     *
     * @param string $errorMessage
     * @return JsonResponse
     */
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
