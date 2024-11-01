<?php

namespace App\Controller;

# Symfony Modules
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

# Models
use App\Document\MobilePaymentBeneficiary;

# ODM
use Doctrine\ODM\MongoDB\DocumentManager;

# Serializer and Normalizers
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

# Services
use App\Service\MobilePaymentBeneficiaryService;
use App\Util\Decode\DecodeUtils;
# Utils
use App\Util\Response\ResponseUtils;
use App\Util\Entity\MobilePaymentBeneficiaryUtils;
use App\Util\Handler\ErrorHandler;

#[Route(path: '/api/v1/beneficiaries')]
class MobilePaymentBeneficiaryController extends AbstractController
{
    private DocumentManager $documentManager;
    private MobilePaymentBeneficiaryService $mobilePaymentBeneficiaryService;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;

        $this->mobilePaymentBeneficiaryService = new MobilePaymentBeneficiaryService(
            $this->documentManager,
        );
    }

    #[Route(path: '/', name: 'beneficiaries_getAll', methods: ['GET'])]
    public function getAll(): Response
    {
        try {
            $documents = $this->mobilePaymentBeneficiaryService->getAll();

            $beneficiaries = MobilePaymentBeneficiaryUtils::getBeneficiaries(
                $documents,
            );

            return ResponseUtils::jsonOk($beneficiaries);
        } catch (\Exception $exception) {
            return ResponseUtils::jsonServerInternalError(
                $exception->getMessage(),
            );
        }
    }

    #[Route(path: '/create', name: 'beneficiaries_create', methods: ['POST'])]
    public function create(
        Request $request,
        ValidatorInterface $validator,
    ): Response {
        try {
            /** @var array{document: string, numberPhone: string, alias: string, bank: string} */
            $jsonData = json_decode($request->getContent(), true);
            $beneficiary = new MobilePaymentBeneficiary();

            $beneficiary
                ->setDocument($jsonData['document'])
                ->setNumberPhone($jsonData['numberPhone'])
                ->setAlias($jsonData['alias'])
                ->setBank($jsonData['bank']);

            # Validator
            $validationErrors = $validator->validate($beneficiary);

            # Serializer and Normalizers
            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            if (count($validationErrors) > 0) {
                $fieldErrors = ErrorHandler::getFieldErrors(
                    [
                        'serializer' => $serializer,
                        'isAssociative' => true,
                    ],
                    $validationErrors,
                );

                return ResponseUtils::jsonBadRequest($fieldErrors);
            }

            # Create a new Document
            $this->mobilePaymentBeneficiaryService->create($beneficiary);

            # Get data of new Document
            $jsonContent = DecodeUtils::getContentOfJson(
                $serializer,
                $beneficiary,
                true,
            );

            return ResponseUtils::jsonCreated($jsonContent);
        } catch (\Exception $exception) {
            return ResponseUtils::jsonServerInternalError(
                $exception->getMessage(),
            );
        }
    }
}
