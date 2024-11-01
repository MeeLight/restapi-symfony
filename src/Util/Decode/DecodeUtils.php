<?php

namespace App\Util\Decode;

use Symfony\Component\Serializer\Serializer;

final class DecodeUtils
{
    /**
     * ## Get Decoded JSON
     * @public
     * @static
     * @param Serializer $serializer
     * @param mixed $data
     * @param ?bool $isAssociative
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
