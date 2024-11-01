<?php

namespace App\Util\Handler;

# Serializer
use Symfony\Component\Serializer\Serializer;

# Utils
use App\Util\Decode\DecodeUtils;

# Interfaces
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ErrorHandler
{
    /**
     * ## Get Fields Errors
     *
     * @public
     * @static
     *
     * @param array{serializer: Serializer, isAssociative: ?bool} $serializerData
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @return array{fields: array<string>, messages: array<string>}
     */
    public static function getFieldErrors(
        array $serializerData,
        ConstraintViolationListInterface $validationErrors,
    ): array {
        $errors = DecodeUtils::getContentOfJson(
            $serializerData['serializer'],
            $validationErrors,
            $serializerData['isAssociative'],
        );

        $fieldErrors = ['fields' => [], 'messages' => []];

        for ($index = 0; $index < count($errors); $index++) {
            $field = $errors[$index]['propertyPath'];
            $errorMessage = $errors[$index]['message'];

            array_push($fieldErrors['fields'], $field);
            array_push($fieldErrors['messages'], $errorMessage);
        }

        return $fieldErrors;
    }
}
