<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Request\LocationDTO;
use App\Entity\Bear;
use App\Repository\BearRepository;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class BearService
{
    private float $degreePerKilometer;

    public function __construct(
        private readonly BearRepository $bearRepository,
        private readonly ValidatorInterface $validator,
    ) {
        $this->degreePerKilometer = 1 / ((2 * M_PI / 360) * 6378.137);
    }

    /**
     * @return Bear[]
     */
    public function getAllBears(): array
    {
        return $this->bearRepository->findAll();
    }

    /**
     * @return Bear[]
     */
    public function findBears(LocationDTO $locationDTO): array
    {
        return $this->bearRepository->findByAdjustedCoordinates(
            $this->getLatitude($locationDTO->getLatitude(), $locationDTO->getRadius()),
            $this->getLatitude($locationDTO->getLatitude(), $locationDTO->getRadius() * -1),
            $this->getLongitude(
                $locationDTO->getLongitude(),
                $locationDTO->getLatitude(),
                $locationDTO->getRadius()
            ),
            $this->getLongitude(
                $locationDTO->getLongitude(),
                $locationDTO->getLatitude(),
                $locationDTO->getRadius() * -1
            ),
        );
    }

    private function getLatitude(float $latitude, int $radius): float
    {
        return $latitude + ($radius * $this->degreePerKilometer);
    }

    private function getLongitude(float $longitude, float $latitude, int $radius): float
    {
        return $longitude + ($radius * $this->degreePerKilometer) / cos($latitude * (M_PI / 180));
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