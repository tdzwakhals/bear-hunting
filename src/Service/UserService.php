<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Random\RandomException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private ValidatorInterface $validator,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
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

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * @throws RandomException
     */
    public function create(User $user): string
    {
        $validationList = $this->validator->validate($user);
        if ($validationList->count() !== 0) {
            throw new ValidationFailedException($user, $validationList);
        }

        $plainPassword = $this->generatePassword();

        $user->setRoles(['ROLE_HUNTER'])
            ->setPassword(
                $this->passwordHasher->hashPassword($user, $plainPassword)
            );

        $this->userRepository->save($user);

        return $plainPassword;
    }

    /**
     * @throws RandomException
     */
    private function generatePassword(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}