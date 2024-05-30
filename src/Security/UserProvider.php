<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final readonly class UserProvider implements UserProviderInterface
{
    public function __construct(private UserService $userService)
    {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return $class = User::class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userService->findOneByIdentifier($identifier);
        if (!$user) {
            throw new UserNotFoundException();
        }

        $this->userService->updateLastLoginForUser($user);
        return $user;
    }
}