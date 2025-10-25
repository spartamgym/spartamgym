<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\PlanRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CardsRepository;


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
    public function crearPlan(Request $request, PlanRepository $planRepository): JsonResponse
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

    #[Route('/panel/crear/targeta', name: 'app_panel_crear_targeta')]
    public function crearTargeta(Request $request, CardsRepository $cardsRepository): JsonResponse
    {
        $id =$request->request->get('id');
        $code = $request->request->get('code');

        if ($id > 0) {
            $card = $cardsRepository->find($id);
            if (!$card instanceof \App\Entity\Cards) {
                return new JsonResponse(['status' => 'error', 'message' => 'Tarjeta no encontrada.'], 404);
            }
            $existingCard = $cardsRepository->findOneBy(['code' => $code]);
            if ($existingCard instanceof \App\Entity\Cards && $existingCard->getId() !== $id) {
                return new JsonResponse(['status' => 'error','message' => 'Ya existe otra tarjeta con este código.'
                ], 400);
            }
        } else {

            $card = $cardsRepository->findOneBy(['code' => $code]);
            if ($card instanceof \App\Entity\Cards) {
                return new JsonResponse(['status' => 'error',  'message' => 'Ya existe otra tarjeta con este código.'], 400);
            }
            $card = new \App\Entity\Cards();
        }
        $card->setCode($code);
        $cardsRepository->save($card, true);

        return new JsonResponse(['status' => 'success', 'message' => 'Tarjeta actualizada exitosamente.']);
    }


    #[Route('/panel/listar/targetas', name: 'app_panel_listar_targetas')]
    public function listarTargetas(CardsRepository $cardsRepository): JsonResponse
    {
        $cards = $cardsRepository->findAll();
        $data = [];
        foreach ($cards as $card) {
            $data[] = [
                'id' => $card->getId(),
                'code' => $card->getCode(),
            ];
        }
        return new JsonResponse($data);
    }
}
