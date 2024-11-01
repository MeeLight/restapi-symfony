<?php

namespace App\Service;

# ODM
use Doctrine\ODM\MongoDB\DocumentManager;

# Models
use App\Document\MobilePaymentBeneficiary;

# Interfaces
use App\Interface\IMobilePaymentBeneficiaryService;

class MobilePaymentBeneficiaryService implements
    IMobilePaymentBeneficiaryService
{
    private DocumentManager $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function getAll(): array
    {
        /** @var MobilePaymentBeneficiary[] */
        $documents = $this->documentManager
            ->getRepository(MobilePaymentBeneficiary::class)
            ->findAll();

        return $documents;
    }

    public function create(MobilePaymentBeneficiary $beneficiary): void
    {
        $this->documentManager->persist($beneficiary);
        $this->documentManager->flush();
    }
}
