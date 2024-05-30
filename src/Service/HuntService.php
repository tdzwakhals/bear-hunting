<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\RankingDTO;
use App\DTO\Request\LocationDTO;
use App\Entity\Bear;
use App\Entity\User;
use App\Exception\BearAlreadyHuntedException;
use App\Factory\RankingDTOFactory;
use App\Repository\BearRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;

final class HuntService
{
    private float $degreePerKilometer;

    public function __construct(
        private readonly BearRepository $bearRepository,
        private readonly Security $security,
        private readonly UserRepository $userRepository,
        private readonly RankingDTOFactory $rankingDTOFactory,
    ) {
        $this->degreePerKilometer = 1 / ((2 * M_PI / 360) * 6378.137);
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
            $this->security->getUser(),
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

    /**
     * @throws BearAlreadyHuntedException
     */
    public function registerHunt(Bear $bear): void
    {
        /** @var User $hunter */
        $hunter = $this->security->getUser();
        if ($bear->getHunters()->contains($hunter)) {
            throw new BearAlreadyHuntedException($bear);
        }

        $bear->addHunter($hunter);
        $this->bearRepository->save($bear);
    }

    /**
     * @return RankingDTO[]
     */
    public function getRankings(): array
    {
        return $this->rankingDTOFactory->makeFromArray(
            $this->userRepository->getRankings()
        );
    }
}