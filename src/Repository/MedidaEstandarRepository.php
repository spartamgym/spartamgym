<?php

namespace App\Repository;

use App\Entity\MedidaEstandar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MedidaEstandar>
 */
class MedidaEstandarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MedidaEstandar::class);
    }

    public function save(MedidaEstandar $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        $flush && $this->getEntityManager()->flush();
    }

    /**
     * @return MedidaEstandar[]
     */
    public function findAllActive(): array
    {
        return $this->findBy(['active' => true], ['nombre' => 'ASC']);
    }
}
