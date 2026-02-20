<?php

namespace App\Repository;

use App\Entity\ColaCards;
use App\Entity\Usuario;
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
        return $this->createQueryBuilder('c')
            ->andWhere('c.ingreso = :ingreso')
            ->setParameter('ingreso', true)
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOpenByCode(string $code): ?ColaCards
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.code = :code')
            ->andWhere('c.ingreso = :ingreso')
            ->setParameter('code', $code)
            ->setParameter('ingreso', true)
            ->orderBy('c.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOpenByUsuario(Usuario $usuario): ?ColaCards
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.usuario = :usuario')
            ->andWhere('c.ingreso = :ingreso')
            ->setParameter('usuario', $usuario)
            ->setParameter('ingreso', true)
            ->orderBy('c.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
