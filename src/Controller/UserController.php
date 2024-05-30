<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user/', name: 'user_')]
final class UserController extends AbstractController
{
    public function __construct(private readonly UserService $userService)
    {
    }

    #[Route('/', name: 'all', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns information on all users',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class, groups: ['fetch:admin']))
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
    #[OA\Tag('Users')]
    #[Security(name: 'Bearer')]
    public function fetchAllAction(): JsonResponse
    {
        return new JsonResponse(
            $this->userService->getAll()
        );
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    #[OA\Post(requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: UserType::class))))]
    #[OA\Response(
        response: 201,
        description: 'Returns confirmation message if user is created'
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
    #[OA\Tag('Users')]
    #[Security(name: 'Bearer')]
    public function createUserAction(Request $request): JsonResponse
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $form->submit(json_decode($request->getContent(), true));

        $password = $this->userService->create($user);

        return new JsonResponse(
            "User {$user->getUserIdentifier()} created successfully. Password is $password.",
            Response::HTTP_CREATED
        );
    }
}