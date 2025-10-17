<?php

namespace App\Repository;

use App\Entity\IdTemp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IdTemp>
 */
class IdTempRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IdTemp::class);
    }


    public function save(IdTemp $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        $flush && $this->getEntityManager()->flush();
    }

    public function getIdentificador(): int
    {
       return $this->createQueryBuilder('i')
            ->select('i.identificador')
            ->where('i.id = :id')
            ->setParameter('id', 1)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
