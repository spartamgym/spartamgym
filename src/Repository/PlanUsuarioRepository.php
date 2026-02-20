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
                SUM(CASE WHEN contabiliza_ingreso = 1 THEN precio ELSE 0 END) as total 
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
        $today = (new \DateTime())->setTime(0, 0, 0);

        $vigentes = $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->where('p.fecha_inicio <= :today')
            ->andWhere('p.fecha_fin >= :today')
            ->setParameter('today', $today)
            ->getQuery()
            ->getSingleScalarResult();

        $programados = $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->where('p.fecha_inicio > :today')
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
            'programados' => $programados,
            'vencidos' => $vencidos
        ];
    }

    public function getTopUsuarios(int $limit = 5): array
    {
        return $this->createQueryBuilder('p')
            ->select('u.nombre', 'SUM(p.precio) as total_gastado', 'count(p.id) as planes_comprados')
            ->join('p.usuario', 'u')
            ->andWhere('p.contabiliza_ingreso = :contabilizaIngreso')
            ->setParameter('contabilizaIngreso', true)
            ->groupBy('u.id')
            ->orderBy('total_gastado', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
