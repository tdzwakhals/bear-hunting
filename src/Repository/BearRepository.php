<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Bear;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bear[] findAll()
 */
final class BearRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bear::class);
    }

    /**
     * @return Bear[]
     */
    public function findByAdjustedCoordinates(
        float $latitudeUp,
        float $latitudeDown,
        float $longitudeLeft,
        float $longitudeRight
    ): array {
        return $this->createQueryBuilder('bear')
            ->where('bear.latitude < :latitudeUp')
            ->andWhere('bear.latitude > :latitudeDown')
            ->andWhere('bear.longitude < :longitudeLeft')
            ->andWhere('bear.longitude > :longitudeRight')
            ->setParameters(new ArrayCollection([
                new Parameter('latitudeUp', $latitudeUp),
                new Parameter('latitudeDown', $latitudeDown),
                new Parameter('longitudeLeft', $longitudeLeft),
                new Parameter('longitudeRight', $longitudeRight),
            ]))
            ->getQuery()
            ->getResult();
    }

    public function save(Bear $bear): void
    {
        $this->getEntityManager()->persist($bear);
        $this->getEntityManager()->flush();
    }

    public function delete(Bear $bear): void
    {
        $this->getEntityManager()->remove($bear);
        $this->getEntityManager()->flush();
    }
}
