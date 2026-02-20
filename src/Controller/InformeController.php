<?php

namespace App\Controller;

use App\Repository\ColaCardsRepository;
use App\Repository\PlanUsuarioRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class InformeController extends AbstractController
{
    #[Route('/informe', name: 'app_informe')]
    public function index(
        PlanUsuarioRepository $planUsuarioRepository,
        ColaCardsRepository $colaCardsRepository,
        UsuarioRepository $usuarioRepository
    ): Response
    {
        $ingresosPorMes = $planUsuarioRepository->getIngresosPorMes();
        $statsPlanes = $planUsuarioRepository->getEstadisticasPlanes();
        $topUsuarios = $planUsuarioRepository->getTopUsuarios();
        $movimientosPorHora = $colaCardsRepository->getMovimientosPorHora();
        $ingresosPorHoraSexoRaw = $colaCardsRepository->getIngresosPorHoraSexo();
        $usuarios = $usuarioRepository->findBy([], ['nombre' => 'ASC']);

        // Calcular total ingresos históricos
        $totalIngresos = array_reduce($ingresosPorMes, function($carry, $item) {
            return $carry + $item['total'];
        }, 0);

        // Ingresos del mes actual
        $mesActual = date('Y-m');
        $ingresosMesActual = 0;
        foreach ($ingresosPorMes as $registro) {
            if ($registro['mes'] === $mesActual) {
                $ingresosMesActual = $registro['total'];
                break;
            }
        }

        $hourLabels = array_map(static fn (int $hour): string => sprintf('%02d:00', $hour), range(0, 23));
        $ingresosHora = array_fill(0, 24, 0);
        $salidasHora = array_fill(0, 24, 0);

        foreach ($movimientosPorHora as $row) {
            $hour = (int)($row['hora'] ?? -1);
            if ($hour < 0 || $hour > 23) {
                continue;
            }

            $ingresosHora[$hour] = (int)($row['ingresos'] ?? 0);
            $salidasHora[$hour] = (int)($row['salidas'] ?? 0);
        }

        $ingresosSexoPorHora = [
            'F' => array_fill(0, 24, 0),
            'M' => array_fill(0, 24, 0),
            'O' => array_fill(0, 24, 0),
        ];

        foreach ($ingresosPorHoraSexoRaw as $row) {
            $hour = (int)($row['hora'] ?? -1);
            if ($hour < 0 || $hour > 23) {
                continue;
            }

            $sexo = strtoupper((string)($row['sexo'] ?? 'O'));
            if (!isset($ingresosSexoPorHora[$sexo])) {
                $sexo = 'O';
            }

            $ingresosSexoPorHora[$sexo][$hour] = (int)($row['total'] ?? 0);
        }

        $findPeakHour = static function (array $values): ?string {
            if (empty($values)) {
                return null;
            }

            $max = max($values);
            if ($max <= 0) {
                return null;
            }

            $peakIndex = array_search($max, $values, true);
            if ($peakIndex === false) {
                return null;
            }

            return sprintf('%02d:00', (int)$peakIndex);
        };

        return $this->render('informe/index.html.twig', [
            'ingresos_chart' => [
                'labels' => array_column($ingresosPorMes, 'mes'),
                'data' => array_column($ingresosPorMes, 'total')
            ],
            'access_hourly_chart' => [
                'labels' => $hourLabels,
                'ingresos' => $ingresosHora,
                'salidas' => $salidasHora,
            ],
            'access_gender_hourly_chart' => [
                'labels' => $hourLabels,
                'femenino' => $ingresosSexoPorHora['F'],
                'masculino' => $ingresosSexoPorHora['M'],
                'otro' => $ingresosSexoPorHora['O'],
            ],
            'access_gender_peaks' => [
                'femenino' => $findPeakHour($ingresosSexoPorHora['F']),
                'masculino' => $findPeakHour($ingresosSexoPorHora['M']),
            ],
            'planes_stats' => $statsPlanes,
            'top_usuarios' => $topUsuarios,
            'total_ingresos' => $totalIngresos,
            'ingresos_mes_actual' => $ingresosMesActual,
            'socios_activos' => $statsPlanes['vigentes'],
            'socios' => array_map(static fn ($usuario): array => [
                'id' => $usuario->getId(),
                'nombre' => (string) ($usuario->getNombre() ?? 'Sin nombre'),
                'cedula' => (string) ($usuario->getCedula() ?? 'Sin cedula'),
            ], $usuarios),
        ]);
    }

    #[Route('/informe/usuario/{id}/promedio-semanal', name: 'app_informe_usuario_promedio_semanal', methods: ['GET'])]
    public function promedioSemanalUsuario(
        int $id,
        UsuarioRepository $usuarioRepository,
        ColaCardsRepository $colaCardsRepository
    ): JsonResponse
    {
        $usuario = $usuarioRepository->find($id);
        if (!$usuario instanceof \App\Entity\Usuario) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        return new JsonResponse([
            'status' => 'success',
            'usuario' => [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'cedula' => $usuario->getCedula(),
            ],
            'chart' => $colaCardsRepository->getPromedioHorasPorDiaSemana($usuario->getId()),
        ]);
    }
}
