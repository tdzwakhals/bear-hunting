<?php

namespace App\Controller;

use App\DTO\Request\LocationDTO;
use App\Form\Request\LocationType;
use App\Service\BearService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/bears')]
final class BearController extends AbstractController
{
    public function __construct(private readonly BearService $bearService)
    {
    }

    #[Route('/', name: 'app_bears', methods: ['GET'])]
    public function fetchAllAction(): JsonResponse
    {
        return new JsonResponse(
            $this->bearService->getAllBears()
        );
    }

    #[Route('/location', name: 'app_bear_by_location', methods: ['POST'])]
    public function fetchBearByLocation(Request $request): JsonResponse
    {
        $locationDTO = new LocationDTO();
        $form = $this->createForm(LocationType::class, $locationDTO);
        $form->handleRequest($request);
        $form->submit(json_decode($request->getContent(), true));

        return new JsonResponse(
            $this->bearService->findBears($locationDTO)
        );
    }
}
