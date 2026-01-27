<?php

namespace App\Repository;

use App\Entity\PlanUsuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlanUsuario>
 */
class PlanUsuarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanUsuario::class);
    }
    public function save(PlanUsuario $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        $flush && $this->getEntityManager()->flush();
    }

    public function getIngresosPorMes(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT 
                DATE_FORMAT(fecha_inicio, '%Y-%m') as mes, 
                SUM(precio) as total 
            FROM plan_usuario 
            WHERE fecha_inicio IS NOT NULL
            GROUP BY mes 
            ORDER BY mes ASC
        ";
        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function getEstadisticasPlanes(): array
    {
        $qb = $this->createQueryBuilder('p');
        $today = new \DateTime();

        $vigentes = $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->where('p.fecha_fin >= :today')
            ->setParameter('today', $today)
            ->getQuery()
            ->getSingleScalarResult();

        $vencidos = $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->where('p.fecha_fin < :today')
            ->setParameter('today', $today)
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'vigentes' => $vigentes,
            'vencidos' => $vencidos
        ];
    }

    public function getTopUsuarios(int $limit = 5): array
    {
        return $this->createQueryBuilder('p')
            ->select('u.nombre', 'SUM(p.precio) as total_gastado', 'count(p.id) as planes_comprados')
            ->join('p.usuario', 'u')
            ->groupBy('u.id')
            ->orderBy('total_gastado', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
