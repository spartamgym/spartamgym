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

    public function getPromedioHorasPorDiaSemana(int $usuarioId): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $rows = $conn->prepare("
            SELECT
                WEEKDAY(c.create_at) AS dia,
                AVG(SIN((2 * PI() * TIME_TO_SEC(TIME(c.create_at))) / 86400)) AS llegada_sin,
                AVG(COS((2 * PI() * TIME_TO_SEC(TIME(c.create_at))) / 86400)) AS llegada_cos,
                AVG(SIN((2 * PI() * TIME_TO_SEC(TIME(c.update_at))) / 86400)) AS salida_sin,
                AVG(COS((2 * PI() * TIME_TO_SEC(TIME(c.update_at))) / 86400)) AS salida_cos,
                AVG(TIMESTAMPDIFF(SECOND, c.create_at, c.update_at)) AS promedio_duracion_sec,
                COUNT(*) AS total_sesiones
            FROM cola_cards c
            WHERE c.usuario_id = :usuarioId
              AND c.create_at IS NOT NULL
              AND c.update_at IS NOT NULL
              AND c.ingreso = 0
              AND c.verificado = 1
              AND c.update_at >= c.create_at
              AND TIMESTAMPDIFF(SECOND, c.create_at, c.update_at) BETWEEN 1 AND 28800
            GROUP BY WEEKDAY(c.create_at)
            ORDER BY WEEKDAY(c.create_at) ASC
        ")->executeQuery([
            'usuarioId' => $usuarioId,
        ])->fetchAllAssociative();

        $labels = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
        $llegadasHoras = array_fill(0, 7, null);
        $salidasHoras = array_fill(0, 7, null);
        $duracionHoras = array_fill(0, 7, null);
        $llegadasLabel = array_fill(0, 7, '--');
        $salidasLabel = array_fill(0, 7, '--');
        $duracionLabel = array_fill(0, 7, '--');
        $sesiones = array_fill(0, 7, 0);

        $totalDuracionAcumuladaSec = 0.0;
        $totalSesiones = 0;

        $formatHour = static function (float $seconds): string {
            $seconds = max(0, min($seconds, 86399));
            $hours = (int) floor($seconds / 3600);
            $minutes = (int) floor(($seconds % 3600) / 60);
            return sprintf('%02d:%02d', $hours, $minutes);
        };

        $formatDuration = static function (float $seconds): string {
            if ($seconds <= 0) {
                return '0m';
            }

            $totalSeconds = (int) round($seconds);
            if ($totalSeconds < 60) {
                return sprintf('%ds', $totalSeconds);
            }

            $minutes = (int) round($totalSeconds / 60);
            $hours = (int) floor($minutes / 60);
            $remainingMinutes = $minutes % 60;

            if ($hours > 0) {
                return sprintf('%dh %02dm', $hours, $remainingMinutes);
            }

            return sprintf('%dm', $remainingMinutes);
        };

        $fromCircularComponentsToSeconds = static function (float $sinValue, float $cosValue): float {
            if (abs($sinValue) < 1e-12 && abs($cosValue) < 1e-12) {
                return 0.0;
            }

            $angle = atan2($sinValue, $cosValue);
            if ($angle < 0) {
                $angle += 2 * M_PI;
            }

            return ($angle / (2 * M_PI)) * 86400;
        };

        foreach ($rows as $row) {
            $dia = (int)($row['dia'] ?? -1);
            if ($dia < 0 || $dia > 6) {
                continue;
            }

            $llegadaSin = (float)($row['llegada_sin'] ?? 0);
            $llegadaCos = (float)($row['llegada_cos'] ?? 0);
            $salidaSin = (float)($row['salida_sin'] ?? 0);
            $salidaCos = (float)($row['salida_cos'] ?? 0);
            $llegadaSec = $fromCircularComponentsToSeconds($llegadaSin, $llegadaCos);
            $salidaSec = $fromCircularComponentsToSeconds($salidaSin, $salidaCos);
            $duracionSec = (float)($row['promedio_duracion_sec'] ?? 0);
            $sesionesDia = (int)($row['total_sesiones'] ?? 0);

            $llegadasHoras[$dia] = round($llegadaSec / 3600, 2);
            $salidasHoras[$dia] = round($salidaSec / 3600, 2);
            $duracionHoras[$dia] = round($duracionSec / 3600, 4);
            $llegadasLabel[$dia] = $formatHour($llegadaSec);
            $salidasLabel[$dia] = $formatHour($salidaSec);
            $duracionLabel[$dia] = $formatDuration($duracionSec);
            $sesiones[$dia] = $sesionesDia;

            $totalDuracionAcumuladaSec += $duracionSec * $sesionesDia;
            $totalSesiones += $sesionesDia;
        }

        $promedioDuracionGeneralSec = $totalSesiones > 0
            ? $totalDuracionAcumuladaSec / $totalSesiones
            : 0.0;
        $promedioDuracionGeneralHoras = round($promedioDuracionGeneralSec / 3600, 4);

        return [
            'labels' => $labels,
            'llegada_horas' => $llegadasHoras,
            'salida_horas' => $salidasHoras,
            'duracion_horas' => $duracionHoras,
            'llegada_label' => $llegadasLabel,
            'salida_label' => $salidasLabel,
            'duracion_label' => $duracionLabel,
            'sesiones' => $sesiones,
            'total_sesiones' => $totalSesiones,
            'promedio_duracion_general_horas' => $promedioDuracionGeneralHoras,
            'promedio_duracion_general_label' => $formatDuration($promedioDuracionGeneralSec),
        ];
    }
}
