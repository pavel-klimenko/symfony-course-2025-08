<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/tests')]
class TesterController extends AbstractController
{
    #[Route('/dev', methods: ['GET'])]
    #[IsGranted('ROLE_TESTER')]
    public function index(): JsonResponse
    {
        return $this->json('API FOR TESTER');
    }
}
