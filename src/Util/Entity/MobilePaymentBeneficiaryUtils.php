<?php

namespace App\Util\Entity;

use App\Document\MobilePaymentBeneficiary;

final class MobilePaymentBeneficiaryUtils
{
    /**
     * ## Get Beneficiaries
     * @param MobilePaymentBeneficiary[] $documents
     * @public
     * @static
     * @return array{
     *     object{
     *         id: ?string,
     *         document: string,
     *         numberPhone: string,
     *         bank: string,
     *         alias: string,
     *         status: bool
     *      }
     * }
     */
    public static function getBeneficiaries(array $documents): array
    {
        /** @var MobilePaymentBeneficiary[] */
        $beneficiaries = [];

        foreach ($documents as $document) {
            array_push($beneficiaries, [
                'id' => $document->getId(),
                'document' => $document->getDocument(),
                'numberPhone' => $document->getNumberPhone(),
                'bank' => $document->getBank(),
                'alias' => $document->getAlias(),
                'status' => $document->getStatus(),
            ]);
        }

        return $beneficiaries;
    }
}
