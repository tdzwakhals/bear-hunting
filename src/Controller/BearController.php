<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Bear;
use App\Form\BearType;
use App\Service\BearService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
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
    #[OA\Response(
        response: 200,
        description: 'Returns information on all bears',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Bear::class, groups: ['fetch', 'fetch:admin']))
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
    #[OA\Tag('Bears')]
    #[Security(name: 'Bearer')]
    public function fetchAllAction(): JsonResponse
    {
        return new JsonResponse(
            $this->bearService->getAllBears()
        );
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    #[OA\Post(requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: BearType::class))))]
    #[OA\Response(
        response: 201,
        description: 'Returns confirmation message if bear is created'
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
        description: 'Internal Server Error or Validation failed exception'
    )]
    #[OA\Tag('Bears')]
    #[Security(name: 'Bearer')]
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
    #[OA\Response(
        response: 200,
        description: 'Returns information on the bear',
        content: new Model(type: Bear::class, groups: ['fetch', 'fetch:admin'])
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
    #[OA\Tag('Bears')]
    #[Security(name: 'Bearer')]
    public function fetchBearAction(Bear $bear): JsonResponse
    {
        return new JsonResponse($bear);
    }

    #[Route('/{id}', name: 'update', requirements: ['id' => '\d+'], methods: ['PUT'])]
    #[OA\Put(requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: BearType::class))))]
    #[OA\Response(
        response: 201,
        description: 'Returns confirmation message if bear is updated'
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
        description: 'Internal Server Error or Validation failed exception'
    )]
    #[OA\Tag('Bears')]
    #[Security(name: 'Bearer')]
    public function updateBearAction(Request $request, Bear $bear): JsonResponse
    {
        $form = $this->createForm(BearType::class, $bear);
        $form->handleRequest($request);
        $form->submit(json_decode($request->getContent(), true));

        $this->bearService->save($bear);

        return new JsonResponse("Bear {$bear->getName()} updated successfully", Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Returns confirmation message if bear is deleted'
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
    #[OA\Tag('Bears')]
    #[Security(name: 'Bearer')]
    public function deleteBearAction(Bear $bear): JsonResponse
    {
        $this->bearService->delete($bear);
        return new JsonResponse("Bear {$bear->getName()} deleted successfully", Response::HTTP_OK);
    }
}