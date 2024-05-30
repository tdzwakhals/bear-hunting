<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;

final readonly class UserService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function findOneByIdentifier(string $identifier): ?User
    {
        return $this->userRepository->findOneBy(['email' => $identifier]);
    }

    public function updateLastLoginForUser(User $user): void
    {
        $user->setLastLogin(new DateTime());
        $this->userRepository->save($user);
    }
}