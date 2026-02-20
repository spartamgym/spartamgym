<?php

namespace App\Repository;

use App\Entity\DatoFisico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Datofisico>
 */
class DatoFisicoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DatoFisico::class);
    }
    public function save(DatoFisico $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        $flush && $this->getEntityManager()->flush();
    }

    public function remove(DatoFisico $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);
        $flush && $this->getEntityManager()->flush();
    }
}
