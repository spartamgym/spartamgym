<?php

namespace App\Repository;

use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Card>
 */
class CardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    public function save(Card $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        $flush && $this->getEntityManager()->flush();
    }

    public function getIdentificador(): int
    {
        return $this->createQueryBuilder('i')
            ->select('i.code')
            ->where('i.id = :id')
            ->setParameter('id', 1)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
