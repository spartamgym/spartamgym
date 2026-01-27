<?php

namespace App\Controller;

use App\Repository\PlanUsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class InformeController extends AbstractController
{
    #[Route('/informe', name: 'app_informe')]
    public function index(PlanUsuarioRepository $planUsuarioRepository): Response
    {
        $ingresosPorMes = $planUsuarioRepository->getIngresosPorMes();
        $statsPlanes = $planUsuarioRepository->getEstadisticasPlanes();
        $topUsuarios = $planUsuarioRepository->getTopUsuarios();

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

        return $this->render('informe/index.html.twig', [
            'ingresos_chart' => [
                'labels' => array_column($ingresosPorMes, 'mes'),
                'data' => array_column($ingresosPorMes, 'total')
            ],
            'planes_stats' => $statsPlanes,
            'top_usuarios' => $topUsuarios,
            'total_ingresos' => $totalIngresos,
            'ingresos_mes_actual' => $ingresosMesActual,
            'socios_activos' => $statsPlanes['vigentes']
        ]);
    }
}
