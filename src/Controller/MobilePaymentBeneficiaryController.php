<?php

declare(strict_types=1);

namespace App\Controller;

use App\Document\MobilePaymentBeneficiary;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/beneficiaries')]
class MobilePaymentBeneficiaryController extends AbstractController
{
    private DocumentManager $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    #[Route(path: '/', name: 'beneficiaries_getAll', methods: ['GET'])]
    public function getAll(): Response
    {
        try {
            $beneficiaryRepository = $this->documentManager->getRepository(
                MobilePaymentBeneficiary::class,
            );

            $queryBuilder = $beneficiaryRepository->createQueryBuilder();
            $beneficiaries = $queryBuilder->getQuery()->execute();

            return new JsonResponse(
                [
                    'message' => 'ok',
                    'status' => 200,
                    'data' => $beneficiaries,
                ],
                200,
            );
        } catch (\Exception $exception) {
            return new JsonResponse(
                [
                    'message' => $exception->getMessage(),
                    'status' => 500,
                    'data' => null,
                ],
                500,
            );
        }
    }

    #[Route(path: '/new', name: 'beneficiaries_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        try {
            /** @var array{document: string, numberPhone: string, alias: string, bank: string} */
            $jsonData = json_decode($request->getContent(), true);

            $beneficiary = new MobilePaymentBeneficiary();

            $beneficiary->document = $jsonData['document'] ?? '';
            $beneficiary->numberPhone = $jsonData['numberPhone'] ?? '';
            $beneficiary->alias = $jsonData['alias'] ?? '';
            $beneficiary->bank = $jsonData['bank'] ?? '';

            $requestBody = [
                'document' => $beneficiary->document,
                'numberPhone' => $beneficiary->numberPhone,
                'alias' => $beneficiary->alias,
                'bank' => $beneficiary->bank,
            ];

            foreach ($requestBody as $key => $value) {
                if ($value === '') {
                    return new JsonResponse(
                        [
                            'message' => "Oops! the field $key is empty",
                            'status' => 400,
                            'data' => null,
                        ],
                        400,
                    );
                }
            }

            $this->documentManager->persist($beneficiary);
            $this->documentManager->flush();

            return new JsonResponse(
                [
                    'message' => 'ok',
                    'status' => 201,
                    'data' => $beneficiary,
                ],
                201,
            );
        } catch (\Exception $exception) {
            return new JsonResponse(
                [
                    'message' => $exception->getMessage(),
                    'status' => 500,
                    'data' => null,
                ],
                500,
            );
        }
    }
}
