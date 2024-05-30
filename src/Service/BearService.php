<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Bear;
use App\Repository\BearRepository;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class BearService
{
    public function __construct(
        private BearRepository $bearRepository,
        private ValidatorInterface $validator,
    ) {

    }

    /**
     * @return Bear[]
     */
    public function getAllBears(): array
    {
        return $this->bearRepository->findAll();
    }

    public function save(Bear $bear): void
    {
        $validationList = $this->validator->validate($bear);
        if ($validationList->count() !== 0) {
            throw new ValidationFailedException($bear, $validationList);
        }

        $this->bearRepository->save($bear);
    }

    public function delete(Bear $bear): void
    {
        $this->bearRepository->delete($bear);
    }
}