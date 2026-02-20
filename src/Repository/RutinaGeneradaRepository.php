<?php

namespace App\Repository;

use App\Entity\RutinaGenerada;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RutinaGenerada>
 */
class RutinaGeneradaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RutinaGenerada::class);
    }

    public function save(RutinaGenerada $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        $flush && $this->getEntityManager()->flush();
    }

    public function countByUsuarioAndRange(Usuario $usuario, \DateTimeImmutable $from, \DateTimeImmutable $to): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.usuario = :usuario')
            ->andWhere('r.createdAt >= :from')
            ->andWhere('r.createdAt < :to')
            ->setParameter('usuario', $usuario)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countByCedulaAndRange(string $cedula, \DateTimeImmutable $from, \DateTimeImmutable $to): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.usuarioCedula = :cedula')
            ->andWhere('r.createdAt >= :from')
            ->andWhere('r.createdAt < :to')
            ->setParameter('cedula', $cedula)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByCedulaLatest(string $cedula, int $limit = 20): array
    {
        $safeLimit = max(1, min($limit, 100));

        return $this->createQueryBuilder('r')
            ->andWhere('r.usuarioCedula = :cedula')
            ->setParameter('cedula', $cedula)
            ->orderBy('r.id', 'DESC')
            ->setMaxResults($safeLimit)
            ->getQuery()
            ->getResult();
    }
}
