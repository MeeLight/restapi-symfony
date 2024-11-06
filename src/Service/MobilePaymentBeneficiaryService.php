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

    /**
     * Get all beneficiaries
     * @public
     * @return array
     */
    public function getAll(): array
    {
        /** @var MobilePaymentBeneficiary[] */
        $documents = $this->documentManager
            ->getRepository(MobilePaymentBeneficiary::class)
            ->findAll();

        return $documents;
    }

    /**
     * Get beneficiary by id
     * @public
     * @param string $id
     * @return ?MobilePaymentBeneficiary
     */
    public function getById(string $id): ?MobilePaymentBeneficiary
    {
        /** @var ?MobilePaymentBeneficiary */
        $document = $this->documentManager
            ->getRepository(MobilePaymentBeneficiary::class)
            ->findOneBy(['_id' => $id]);

        return $document;
    }

    /**
     * Create a beneficiary
     * @param MobilePaymentBeneficiary $beneficiary
     * @return void
     */
    public function create(MobilePaymentBeneficiary $beneficiary): void
    {
        $this->documentManager->persist($beneficiary);
        $this->documentManager->flush();
    }

    /**
     * Update a beneficiary
     * @return void
     */
    public function update(): void
    {
        $this->documentManager->flush();
    }

    /**
     * Delete a beneficiary
     * @return void
     */
    public function delete(string $id): void
    {
        $this->documentManager
            ->createQueryBuilder(MobilePaymentBeneficiary::class)
            ->remove()
            ->field('_id')
            ->equals($id)
            ->getQuery()
            ->execute();
    }
}
