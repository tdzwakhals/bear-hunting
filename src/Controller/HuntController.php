<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Request\LocationDTO;
use App\Entity\Bear;
use App\Form\LocationType;
use App\Service\HuntService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/hunt', name: 'app_hunt_')]
final class HuntController extends AbstractController
{
    public function __construct(private readonly HuntService $huntService)
    {
    }

    #[Route('/location', name: 'by_location', methods: ['POST'])]
    public function fetchHuntByLocationAction(Request $request): JsonResponse
    {
        $locationDTO = new LocationDTO();
        $form = $this->createForm(LocationType::class, $locationDTO);
        $form->handleRequest($request);
        $form->submit(json_decode($request->getContent(), true));

        return new JsonResponse(
            $this->huntService->findBears($locationDTO)
        );
    }

    #[Route('/bear/{id}', name: 'hunt', methods: ['PUT'])]
    public function registerHuntAction(Bear $bear): JsonResponse
    {
        $this->huntService->completeHunt($bear);
        return new JsonResponse("Hunt of {$bear->getName()} registered!");
    }

    #[Route('/rankings', name: 'rankings', methods: ['GET'])]
    public function getRankingsAction(): JsonResponse
    {
        return new JsonResponse(
            $this->huntService->getRankings()
        );
    }
}
