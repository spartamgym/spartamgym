<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\PlanRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CardsRepository;
use App\Repository\UsuarioRepository;
use App\Repository\PlanUsuarioRepository;


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
        $id = $request->request->get('id');
        $code = $request->request->get('code');
        //validar que venga valor de code
        if (!$code) {
            return new JsonResponse(['status' => 'error', 'message' => 'Falta el codigo de la tarjeta.'], 400);
        }

        if ($id > 0) {
            $card = $cardsRepository->find($id);
            if (!$card instanceof \App\Entity\Cards) {
                return new JsonResponse(['status' => 'error', 'message' => 'Tarjeta no encontrada.'], 404);
            }
            $existingCard = $cardsRepository->findOneBy(['code' => $code]);
            if ($existingCard instanceof \App\Entity\Cards && $existingCard->getId() !== $id) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Ya existe otra tarjeta con este código.'
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
                'usuario' => $card->getUsuario() ? $card->getUsuario()->getNombre() : 'sin asignar',
                'status' => $card->isActive()?'activo':'inactivo',
                'created_at' => $card->getCreateAt()->format('Y-m-d H:i:s'),
                'updated_at' => $card->getUpdateAt()->format('Y-m-d H:i:s'),

            ];
        }
        return new JsonResponse($data);
    }

    #[Route('/panel/vincular_targeta', name: 'app_panel_vincular_targeta')]
    public function vincularTargeta(): Response
    {
        return $this->render('panel/vincular_targeta.html.twig');
    }

    #[Route('/panel/listar/targetas/disponibles', name: 'app_panel_listar_targetas_disponibles')]
    public function listarTargetasDisponibles(CardsRepository $cardsRepository): JsonResponse
    {
        //obtener targetas que no esten vinculadas a ningun usuario

        $cards = $cardsRepository->findUnlinkedCards();
        $data = [];
        foreach ($cards as $card) {
            $data[] = [
                'id' => $card->getId(),
                'code' => $card->getCode(),
            ];
        }
        return new JsonResponse($data);
    }

    #[Route('/panel/listar/usuarios/disponibles', name: 'app_panel_listar_usuarios_disponibles')]
    public function listarUsuariosDisponibles(UsuarioRepository $usuarioRepository): JsonResponse
    {
        //obtener targetas que no esten vinculadas a ningun usuario

        $usuarios = $usuarioRepository->findUnlinkedUsuarios();
        $data = [];
        foreach ($usuarios as $usuario) {
            $data[] = [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'cedula' => $usuario->getCedula(),
            ];
        }
        return new JsonResponse($data);
    }

    #[Route('/panel/vincular_targeta_procesar', name: 'app_panel_vincular_targeta_procesar')]
    public function vincularUnaTargeta(
        Request $request,
        CardsRepository $cardsRepository,
        UsuarioRepository $usuarioRepository
    ): JsonResponse {
        $idCard = $request->request->get('id_card');
        $idUsuario = $request->request->get('id_usuario');

        //buscar la targeta y el usuario por id
        $card = $cardsRepository->find($idCard);
        $usuario = $usuarioRepository->find($idUsuario);
        //verificar que existan
        if (!$card instanceof \App\Entity\Cards) {
            return new JsonResponse(['status' => 'error', 'message' => 'Tarjeta no encontrada.'], 404);
        }
        if (!$usuario instanceof \App\Entity\Usuario) {
            return new JsonResponse(['status' => 'error', 'message' => 'Usuario no encontrado.'], 404);
        }
        $usuario->setCard($card);
        $usuarioRepository->save($usuario, true);
        //luego vincular la targeta al usuario

        return new JsonResponse(['status' => 'success', 'message' => 'Tarjeta vinculada al usuario exitosamente.']);
    }


    #[Route('/panel/vincular_usuarios_sin_plan', name: 'app_panel_vincular_usuarios_sin_plan')]
    public function usuariosSinPlanView(): Response
    {
        return $this->render('panel/vincular_usuarios_sin_plan.html.twig');
    }
    #[Route('/panel/listar_usuario_sin_plan', name: 'app_panel_usuarios_sin_plan')]
    public function usuariosSinPlan(UsuarioRepository $usuarioRepository): JsonResponse
    {
        $usuarios = $usuarioRepository->findAll();

        $data = [];
        foreach ($usuarios as $usuario) {
            $data[] = [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'cedula' => $usuario->getCedula(),
            ];
        }
        return new JsonResponse($data);
    }

    #[Route('/panel/vincular_usuario_plan', name: 'app_panel_vincular_usuario_plan')]
    public function vincularUsuarioPlan(
        Request $request,
        UsuarioRepository $usuarioRepository,
        PlanRepository $planRepository,
        PlanUsuarioRepository $planUsuarioRepository
    ): JsonResponse {
        $idUsuario = $request->request->get('id_usuario');
        $idPlan = $request->request->get('id_plan');    
        //buscar el usuario y el plan por id
        $usuario = $usuarioRepository->find($idUsuario);
        $plan = $planRepository->find($idPlan);
        //verificar que existan
        if (!$usuario instanceof \App\Entity\Usuario) {
            return new JsonResponse(['status' => 'error', 'message' => 'Usuario no encontrado.'], 404);
        }
        if (!$plan instanceof \App\Entity\Plan) {
            return new JsonResponse(['status' => 'error', 'message' => 'Plan no encontrado.'], 404);
        }
        //validar que no haya un plan activo
        if ($usuario->hasActivePlan()) {
           // return new JsonResponse(['status' => 'error', 'message' => 'El usuario ya tiene un plan activo.'], 400);
        }
        //luego vincular el plan al usuario
        $planUsuario = new \App\Entity\PlanUsuario();
        $planUsuario->setUsuario($usuario);
        $planUsuario->setPlan($plan);
        $planUsuarioRepository->save($planUsuario);
       
        return new JsonResponse(['status' => 'success', 'message' => 'Plan vinculado al usuario exitosamente.']);
    }


}
