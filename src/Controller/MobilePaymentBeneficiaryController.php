<?php

namespace App\Controller;

# Symfony Modules
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

# Models
use App\Document\MobilePaymentBeneficiary;

# ODM
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;

# Services
use App\Service\MobilePaymentBeneficiaryService;

# Utils
use App\Util\Decode\DecodeUtils;
use App\Util\Response\ResponseUtils;
use App\Util\Entity\MobilePaymentBeneficiaryUtils;
use App\Util\Handler\ErrorHandler;

#[Route(path: '/api/v1/beneficiaries')]
class MobilePaymentBeneficiaryController extends AbstractController
{
    private DocumentManager $documentManager;
    private MobilePaymentBeneficiaryService $mobilePaymentBeneficiaryService;
    private array $encoders;
    private array $normalizers;
    private Serializer $serializer;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;

        $this->mobilePaymentBeneficiaryService = new MobilePaymentBeneficiaryService(
            $this->documentManager,
        );

        $this->encoders = [new JsonEncoder()];
        $this->normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($this->normalizers, $this->encoders);
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
        } catch (MongoDBException $exception) {
            return ResponseUtils::jsonServerInternalError(
                $exception->getMessage(),
            );
        }
    }

    #[Route(path: '/{id}', name: 'beneficiaries_getById', methods: ['GET'])]
    public function getById(string $id): Response
    {
        try {
            $document = $this->mobilePaymentBeneficiaryService->getById(
                trim($id),
            );

            if (is_null($document)) {
                return ResponseUtils::jsonBadRequest([
                    'message' => 'Oops! Id not found!',
                ]);
            }

            $beneficiary = MobilePaymentBeneficiaryUtils::getBeneficiary(
                $document,
            );

            return ResponseUtils::jsonOk($beneficiary);
        } catch (MongoDBException $exception) {
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
            /** @var array
             * {
             *     document: string,
             *     numberPhone: string,
             *     alias: string,
             *     bank: string,
             * }
             */
            $reqBody = DecodeUtils::jsonToReqBody($request);
            $beneficiary = new MobilePaymentBeneficiary();

            $beneficiary
                ->setDocument(trim($reqBody['document']))
                ->setNumberPhone(trim($reqBody['numberPhone']))
                ->setAlias(trim($reqBody['alias']))
                ->setBank(trim($reqBody['bank']));

            # Validator
            $validationErrors = $validator->validate($beneficiary);

            if (count($validationErrors) > 0) {
                $fieldErrors = ErrorHandler::getFieldErrors(
                    [
                        'serializer' => $this->serializer,
                        'isAssociative' => true,
                    ],
                    $validationErrors,
                );

                return ResponseUtils::jsonBadRequest([
                    'errors' => $fieldErrors,
                ]);
            }

            # Create a new Document
            $this->mobilePaymentBeneficiaryService->create($beneficiary);

            # Get data of new Document
            $json = DecodeUtils::getContentOfJson(
                $this->serializer,
                $beneficiary,
                true,
            );

            return ResponseUtils::jsonCreated($json);
        } catch (MongoDBException $exception) {
            return ResponseUtils::jsonServerInternalError(
                $exception->getMessage(),
            );
        }
    }

    #[Route(path: '/{id}', name: 'beneficiaries_update', methods: ['PATCH'])]
    public function update(
        Request $request,
        ValidatorInterface $validator,
        string $id,
    ): Response {
        try {
            # Verificar si existe el beneficiario
            $beneficiaryFound = $this->mobilePaymentBeneficiaryService->getById(
                trim($id),
            );

            if (is_null($beneficiaryFound)) {
                return ResponseUtils::jsonBadRequest([
                    'message' => 'Oops! Id not found!',
                ]);
            }

            /** @var array
             * {
             *     document: string,
             *     numberPhone: string,
             *     alias: string,
             *     bank: string,
             * }
             */
            $reqBody = DecodeUtils::jsonToReqBody($request);

            if (array_key_exists('document', $reqBody)) {
                $beneficiaryFound->setDocument(trim($reqBody['document']));
            }

            if (array_key_exists('numberPhone', $reqBody)) {
                $beneficiaryFound->setNumberPhone(
                    trim($reqBody['numberPhone']),
                );
            }

            if (array_key_exists('bank', $reqBody)) {
                $beneficiaryFound->setBank(trim($reqBody['bank']));
            }

            if (array_key_exists('alias', $reqBody)) {
                $beneficiaryFound->setAlias(trim($reqBody['alias']));
            }

            # Validator
            $validationErrors = $validator->validate($beneficiaryFound);

            if (count($validationErrors) > 0) {
                $fieldErrors = ErrorHandler::getFieldErrors(
                    [
                        'serializer' => $this->serializer,
                        'isAssociative' => true,
                    ],
                    $validationErrors,
                );

                return ResponseUtils::jsonBadRequest([
                    'errors' => $fieldErrors,
                ]);
            }

            # Update a Document Found
            $this->mobilePaymentBeneficiaryService->update();

            # Get data of updated Document
            $json = DecodeUtils::getContentOfJson(
                $this->serializer,
                $beneficiaryFound,
                true,
            );

            return ResponseUtils::jsonOk($json);
        } catch (MongoDBException $exception) {
            return ResponseUtils::jsonServerInternalError(
                $exception->getMessage(),
            );
        }
    }
}
