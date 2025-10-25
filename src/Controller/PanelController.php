<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\PlanRepository;
use Symfony\Component\HttpFoundation\Request;

final class PanelController extends AbstractController
{
    #[Route('/', name: 'app_panel')]
    public function index(): Response
    {
        return $this->render('panel/index.html.twig');
    }

    #[Route('/panel/planes', name: 'app_panel_planes')]
    public function planes(): Response
    {
        return $this->render('panel/planes.html.twig'); 
    }

    #[Route('/panel/planes/crear', name: 'app_panel_planes_crear')]
    public function crearPlan(Request $request,PlanRepository $planRepository): JsonResponse
    {   
          $id = $request->request->get('id');
            $nombre = $request->request->get('nombre');
            $precio = $request->request->get('precio');
            $tiempo = $request->request->get('tiempo');
            $detalle = $request->request->get('detalle');

            if ($id > 0) {
                $plan = $planRepository->find($id);
                if (!$plan instanceof \App\Entity\Plan) {
                    return new JsonResponse(['status' => 'error', 'message' => 'Plan no encontrado.'], 404);
                }
            } else {
                $plan = new \App\Entity\Plan();
            }
            $plan->setNombre($nombre);
            $plan->setPrecio((int)$precio);
            $plan->setTiempo($tiempo);
            $detalleArray = array_map('trim', explode(',', $detalle));
            $plan->setDetalle($detalleArray);   
            $planRepository->save($plan);

        return  new JsonResponse(['status' => 'success', 'message' => 'Plan guardado exitosamente.']);
    }



    #[Route('/panel/planes/listar', name: 'app_panel_planes_listar')]
    public function listarPlanes(PlanRepository $planesRepository): JsonResponse
    {
        $plans = $planesRepository->findAll();
        $data = [];
        foreach ($plans as $plan) {
            $data[] = [
                'id' => $plan->getId(),
                'nombre' => $plan->getNombre(),     
                'precio' => $plan->getPrecio(),
                'tiempo' => $plan->getTiempo(),
                'detalle' => $plan->getDetalleToString(),
              
            ];
        }
        return new JsonResponse($data);
  
    }        

    #[Route('/panel/targetas_ingreso', name: 'app_panel_targetas_ingreso')]
    public function targetasIngreso(): Response
    {
        return $this->render('panel/targetas_ingreso.html.twig');
    }

}
