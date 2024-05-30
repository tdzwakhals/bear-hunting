<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
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
    public function fetchAllAction(): JsonResponse
    {
        return new JsonResponse(
            $this->userService->getAll()
        );
    }

    #[Route('/', name: 'create', methods: ['POST'])]
    public function createAction(Request $request): JsonResponse
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