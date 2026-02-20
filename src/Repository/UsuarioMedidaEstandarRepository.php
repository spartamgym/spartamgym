<?php

namespace App\Repository;

use App\Entity\Usuario;
use App\Entity\UsuarioMedidaEstandar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UsuarioMedidaEstandar>
 */
class UsuarioMedidaEstandarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsuarioMedidaEstandar::class);
    }

    public function save(UsuarioMedidaEstandar $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        $flush && $this->getEntityManager()->flush();
    }

    public function findActiveByUsuario(Usuario $usuario): ?UsuarioMedidaEstandar
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.usuario = :usuario')
            ->andWhere('u.active = :active')
            ->setParameter('usuario', $usuario)
            ->setParameter('active', true)
            ->orderBy('u.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return UsuarioMedidaEstandar[]
     */
    public function findAllActiveByUsuario(Usuario $usuario): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.usuario = :usuario')
            ->andWhere('u.active = :active')
            ->setParameter('usuario', $usuario)
            ->setParameter('active', true)
            ->orderBy('u.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
