<?php

namespace App\Repository;

use App\Entity\ColaCards;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ColaCards>
 */
class ColaCardsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ColaCards::class);
    }

    public function save(ColaCards $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
       $flush && $this->getEntityManager()->flush();
    }

    public function getAllCardsActive(): array
    {
        return $this->findBy(['verificado' => false]);
    }
}
