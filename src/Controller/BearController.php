<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Bear;
use App\Form\BearType;
use App\Service\BearService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/bear/', name: 'app_bear_')]
final class BearController extends AbstractController
{
    public function __construct(private readonly BearService $bearService)
    {
    }

    #[Route('/', name: 'all', methods: ['GET'])]
    public function fetchAllAction(): JsonResponse
    {
        return new JsonResponse(
            $this->bearService->getAllBears()
        );
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function createBearAction(Request $request): JsonResponse
    {
        $bear = new Bear();
        $form = $this->createForm(BearType::class, $bear);
        $form->handleRequest($request);
        $form->submit(json_decode($request->getContent(), true));

        $this->bearService->save($bear);

        return new JsonResponse("Bear {$bear->getName()} created successfully", Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'fetch', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function fetchBearAction(Bear $bear): JsonResponse
    {
        return new JsonResponse($bear);
    }

    #[Route('/{id}', name: 'update', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function updateBearAction(Request $request, Bear $bear): JsonResponse
    {
        $form = $this->createForm(BearType::class, $bear);
        $form->handleRequest($request);
        $form->submit(json_decode($request->getContent(), true));

        $this->bearService->save($bear);

        return new JsonResponse("Bear {$bear->getName()} updated successfully", Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function deleteBearAction(Bear $bear): JsonResponse
    {
        $this->bearService->delete($bear);
        return new JsonResponse("Bear {$bear->getName()} deleted successfully", Response::HTTP_NO_CONTENT);
    }
}