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

    public function getMovimientosPorHora(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $ingresosRows = $conn->prepare("
            SELECT HOUR(create_at) AS hora, COUNT(*) AS total
            FROM cola_cards
            WHERE create_at IS NOT NULL
            GROUP BY HOUR(create_at)
        ")->executeQuery()->fetchAllAssociative();

        $salidasRows = $conn->prepare("
            SELECT HOUR(update_at) AS hora, COUNT(*) AS total
            FROM cola_cards
            WHERE update_at IS NOT NULL
              AND ingreso = 0
              AND verificado = 1
            GROUP BY HOUR(update_at)
        ")->executeQuery()->fetchAllAssociative();

        $ingresos = array_fill(0, 24, 0);
        foreach ($ingresosRows as $row) {
            $hora = (int)($row['hora'] ?? -1);
            if ($hora >= 0 && $hora <= 23) {
                $ingresos[$hora] = (int)$row['total'];
            }
        }

        $salidas = array_fill(0, 24, 0);
        foreach ($salidasRows as $row) {
            $hora = (int)($row['hora'] ?? -1);
            if ($hora >= 0 && $hora <= 23) {
                $salidas[$hora] = (int)$row['total'];
            }
        }

        $result = [];
        for ($hora = 0; $hora < 24; $hora++) {
            $result[] = [
                'hora' => $hora,
                'ingresos' => $ingresos[$hora],
                'salidas' => $salidas[$hora],
            ];
        }

        return $result;
    }

    public function getIngresosPorHoraSexo(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        return $conn->prepare("
            SELECT
                HOUR(c.create_at) AS hora,
                CASE UPPER(COALESCE(NULLIF(u.sexo, ''), 'O'))
                    WHEN 'F' THEN 'F'
                    WHEN 'M' THEN 'M'
                    ELSE 'O'
                END AS sexo,
                COUNT(*) AS total
            FROM cola_cards c
            INNER JOIN usuario u ON u.id = c.usuario_id
            WHERE c.create_at IS NOT NULL
            GROUP BY HOUR(c.create_at), sexo
            ORDER BY HOUR(c.create_at) ASC
        ")->executeQuery()->fetchAllAssociative();
    }
}
