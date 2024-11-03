<?php

namespace App\Util\Decode;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

final class DecodeUtils
{
    /**
     * ## Convert JSON (string) to Request Body (associative array)
     *
     * @public
     * @static
     *
     * @param Request $request
     * @param bool $isAssociative
     *
     * @return mixed
     */
    public static function jsonToReqBody(
        Request $request,
        bool $isAssociative = true,
    ): mixed {
        $object = json_decode($request->getContent(), $isAssociative);

        return $object;
    }

    /**
     * ## Get Decoded JSON
     *
     * @public
     * @static
     *
     * @param Serializer $serializer
     * @param mixed $data
     * @param ?bool $isAssociative
     *
     * @return mixed
     */
    public static function getContentOfJson(
        Serializer $serializer,
        mixed $data,
        ?bool $isAssociative = null,
    ) {
        $content = json_decode(
            $serializer->serialize($data, 'json'),
            $isAssociative,
        );

        return $content;
    }
}
