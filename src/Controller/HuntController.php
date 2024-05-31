<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\RankingDTO;
use App\DTO\Request\LocationDTO;
use App\Entity\Bear;
use App\Form\LocationType;
use App\Service\HuntService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/hunt', name: 'app_hunt_')]
final class HuntController extends AbstractFOSRestController
{
    public function __construct(
        private readonly HuntService $huntService,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/location', name: 'by_location', methods: ['POST'])]
    #[OA\Post(requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: LocationType::class))))]
    #[OA\Response(
        response: 200,
        description: 'Returns information on all bears available to hunt',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Bear::class, groups: ['fetch']))
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Invalid JWT Token'
    )]
    #[OA\Response(
        response: 429,
        description: 'Too many requests'
    )]
    #[OA\Response(
        response: 500,
        description: 'Internal Server Error'
    )]
    #[OA\Tag('Hunt')]
    #[Security(name: 'Bearer')]
    public function fetchHuntByLocationAction(Request $request): Response
    {
        $locationDTO = new LocationDTO();
        $form = $this->createForm(LocationType::class, $locationDTO);
        $form->handleRequest($request);
        $form->submit(json_decode($request->getContent(), true));

        $data = $this->serializer->normalize(
            $this->huntService->findBears($locationDTO),
            context: ['groups' => ['fetch']]
        );

        return $this->handleView(
            $this->view($data)
        );
    }

    #[Route('/bear/{id}', name: 'hunt', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Returns confirmation message if bear hunt was registered'
    )]
    #[OA\Response(
        response: 401,
        description: 'Invalid JWT Token'
    )]
    #[OA\Response(
        response: 429,
        description: 'Too many requests'
    )]
    #[OA\Response(
        response: 500,
        description: 'Internal Server Error'
    )]
    #[OA\Tag('Hunt')]
    #[Security(name: 'Bearer')]
    public function registerHuntAction(Bear $bear): Response
    {
        $this->huntService->registerHunt($bear);

        return $this->handleView(
            $this->view("Hunt of {$bear->getName()} registered!")
        );
    }

    #[Route('/rankings', name: 'rankings', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns rankings of all hunters with amount of hunted bears',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: RankingDTO::class))
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Invalid JWT Token'
    )]
    #[OA\Response(
        response: 429,
        description: 'Too many requests'
    )]
    #[OA\Response(
        response: 500,
        description: 'Internal Server Error'
    )]
    #[OA\Tag('Hunt')]
    #[Security(name: 'Bearer')]
    public function getRankingsAction(): Response
    {
        $data = $this->serializer->normalize($this->huntService->getRankings());

        return $this->handleView(
            $this->view($data)
        );
    }
}
