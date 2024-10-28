<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PagoMovilController extends AbstractController
{
    #[Route('/api/v1/beneficiarios')]
    public function index(): Response
    {
        $data = [
            [
                'document' => '28123456',
                'numberPhone' => '04121234567',
                'alias' => 'Moises',
                'bank' => [
                    'code' => '0102',
                    'name' => 'BANCO DE VENEZUELA',
                ],
            ],
            [
                'document' => '30123456',
                'numberPhone' => '04141234567',
                'alias' => 'Victoria',
                'bank' => [
                    'code' => '0102',
                    'name' => 'BANCO DE VENEZUELA',
                ],
            ],
        ];

        return $this->json($data);
    }
}
