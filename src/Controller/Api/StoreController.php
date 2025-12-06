<?php

namespace App\Controller\Api;

use App\Domain\Entity\Store;
use App\Service\StoreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/stores')]
class StoreController extends AbstractController
{
    public function __construct(
        private readonly StoreService $storeService
    ) {}

    #[Route('', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(): JsonResponse
    {
        $stores = $this->storeService->getAll();
        return $this->json($stores);
    }

    #[Route('/{id}', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Store $store): JsonResponse
    {
        return $this->json($this->storeService->getStore($store));
    }

    #[Route('', methods: ['POST'])]
    #[IsGranted('ROLE_STORE_MANAGER')]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $store = $this->storeService->create($data);
        return $this->json($store, Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    #[IsGranted('ROLE_STORE_MANAGER')]
    public function update(Store $store, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $store = $this->storeService->update($store, $data);
        return $this->json($store);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    #[IsGranted('ROLE_STORE_MANAGER')]
    public function delete(Store $store): JsonResponse
    {
        $this->storeService->delete($store);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
