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
        $maxBeneficiarios = (int)$request->request->get('max_beneficiarios', 1);

        if ($id > 0) {
            $plan = $planRepository->find($id);
            if (!$plan instanceof \App\Entity\Plan) {
                return new JsonResponse(['status' => 'error', 'message' => 'Plan no encontrado.'], 404);
            }
        } else {
            $plan = new \App\Entity\Plan();
        }
        if ($maxBeneficiarios < 1) {
            return new JsonResponse(['status' => 'error', 'message' => 'La cantidad de beneficiarios debe ser mayor o igual a 1.'], 400);
        }
        $plan->setNombre($nombre);
        $plan->setPrecio((int)$precio);
        $plan->setTiempo($tiempo);
        $detalleArray = array_map('trim', explode(',', $detalle));
        $plan->setDetalle($detalleArray);
        $plan->setMaxBeneficiarios($maxBeneficiarios);
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
                'max_beneficiarios' => $plan->getMaxBeneficiarios(),

            ];
        }
        return new JsonResponse($data);
    }

    #[Route('/panel/planes/eliminar', name: 'app_panel_planes_eliminar', methods: ['POST'])]
    public function eliminarPlan(Request $request, PlanRepository $planRepository): JsonResponse
    {
        $id = (int) $request->request->get('id', 0);
        if ($id <= 0) {
            return new JsonResponse(['status' => 'error', 'message' => 'ID de plan inválido.'], 400);
        }

        $plan = $planRepository->find($id);
        if (!$plan instanceof \App\Entity\Plan) {
            return new JsonResponse(['status' => 'error', 'message' => 'Plan no encontrado.'], 404);
        }

        $planRepository->remove($plan);

        return new JsonResponse(['status' => 'success', 'message' => 'Plan eliminado correctamente.']);
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

    #[Route('/panel/targeta/eliminar', name: 'app_panel_eliminar_targeta', methods: ['POST'])]
    public function eliminarTargeta(Request $request, CardsRepository $cardsRepository): JsonResponse
    {
        $id = (int) $request->request->get('id', 0);
        if ($id <= 0) {
            return new JsonResponse(['status' => 'error', 'message' => 'ID de tarjeta inválido.'], 400);
        }

        $card = $cardsRepository->find($id);
        if (!$card instanceof \App\Entity\Cards) {
            return new JsonResponse(['status' => 'error', 'message' => 'Tarjeta no encontrada.'], 404);
        }

        if ($card->getUsuario() !== null) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'No se puede eliminar una tarjeta vinculada. Primero desvincúlala del usuario.'
            ], 409);
        }

        $cardsRepository->remove($card);

        return new JsonResponse(['status' => 'success', 'message' => 'Tarjeta eliminada correctamente.']);
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
            $estadoPlan = 'sin_plan';
            $estadoPlanLabel = 'Sin plan';
            $planBloqueado = false;
            $hasVencido = false;
            $hasProgramado = false;

            foreach ($usuario->getPlan() as $planUsuario) {
                $estado = $planUsuario->statusPlanEstado();
                if ($estado === 'vigente') {
                    $estadoPlan = 'vigente';
                    $estadoPlanLabel = 'Vigente';
                    $planBloqueado = true;
                    break;
                }
                if ($estado === 'programado') {
                    $hasProgramado = true;
                } elseif ($estado === 'vencido') {
                    $hasVencido = true;
                }
            }

            if ($estadoPlan !== 'vigente') {
                if ($hasProgramado) {
                    $estadoPlan = 'programado';
                    $estadoPlanLabel = 'Programado';
                    $planBloqueado = true;
                } elseif ($hasVencido) {
                    $estadoPlan = 'vencido';
                    $estadoPlanLabel = 'Vencido';
                }
            }

            $data[] = [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'cedula' => $usuario->getCedula(),
                'plan_status' => $estadoPlan,
                'plan_status_label' => $estadoPlanLabel,
                'plan_blocked' => $planBloqueado,
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
        $idsUsuariosRaw = [];
        try {
            $idsUsuariosRaw = $request->request->all('id_usuarios');
        } catch (\Throwable) {
            $idUsuariosEscalar = $request->request->get('id_usuarios');
            if (!empty($idUsuariosEscalar)) {
                $idsUsuariosRaw[] = $idUsuariosEscalar;
            }
        }

        $idUsuario = $request->request->get('id_usuario');
        if (!empty($idUsuario)) {
            $idsUsuariosRaw[] = $idUsuario;
        }

        $idsUsuarios = array_values(array_unique(array_filter(
            array_map(static fn($id) => (int)$id, $idsUsuariosRaw),
            static fn($id) => $id > 0
        )));
        $idPlan = $request->request->get('id_plan');
        $fechaInicioRaw = $request->request->get('fecha_inicio');
        //buscar el plan por id
        $plan = $planRepository->find($idPlan);
        //verificar que existan
        if (count($idsUsuarios) === 0) {
            return new JsonResponse(['status' => 'error', 'message' => 'Debe seleccionar al menos un usuario.'], 400);
        }
        if (!$plan instanceof \App\Entity\Plan) {
            return new JsonResponse(['status' => 'error', 'message' => 'Plan no encontrado.'], 404);
        }

        $maxBeneficiarios = $plan->getMaxBeneficiarios();
        if (count($idsUsuarios) > $maxBeneficiarios) {
            return new JsonResponse([
                'status' => 'error',
                'message' => sprintf('El plan "%s" solo permite %d beneficiario(s).', $plan->getNombre(), $maxBeneficiarios)
            ], 400);
        }

        $usuariosEncontrados = $usuarioRepository->findBy(['id' => $idsUsuarios]);
        $usuariosPorId = [];
        foreach ($usuariosEncontrados as $usuarioEncontrado) {
            $usuariosPorId[$usuarioEncontrado->getId()] = $usuarioEncontrado;
        }
        foreach ($idsUsuarios as $idUsuarioSeleccionado) {
            if (!isset($usuariosPorId[$idUsuarioSeleccionado])) {
                return new JsonResponse(['status' => 'error', 'message' => sprintf('Usuario con ID %d no encontrado.', $idUsuarioSeleccionado)], 404);
            }
        }

        $usuariosConPlanVigente = [];
        foreach ($idsUsuarios as $idUsuarioSeleccionado) {
            $usuarioValidar = $usuariosPorId[$idUsuarioSeleccionado];
            foreach ($usuarioValidar->getPlan() as $planUsuarioExistente) {
                if (in_array($planUsuarioExistente->statusPlanEstado(), ['vigente', 'programado'], true)) {
                    $usuariosConPlanVigente[] = $usuarioValidar->getNombre() ?: ('ID ' . $idUsuarioSeleccionado);
                    break;
                }
            }
        }
        if (count($usuariosConPlanVigente) > 0) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'No se puede asignar plan porque ya tienen uno vigente/programado: ' . implode(', ', $usuariosConPlanVigente),
            ], 409);
        }

        $fechaInicio = null;
        if (!empty($fechaInicioRaw)) {
            try {
                $fechaInicio = new \DateTime($fechaInicioRaw);
                $fechaInicio->setTime(0, 0, 0);
            } catch (\Throwable) {
                return new JsonResponse(['status' => 'error', 'message' => 'La fecha de inicio es invalida.'], 400);
            }
        }

        $totalBeneficiariosGrupo = count($idsUsuarios);
        $grupoCompartido = null;
        if ($totalBeneficiariosGrupo > 1) {
            try {
                $grupoCompartido = 'PC-' . strtoupper(bin2hex(random_bytes(8)));
            } catch (\Throwable) {
                $grupoCompartido = 'PC-' . strtoupper(uniqid('', true));
            }
        }

        foreach ($idsUsuarios as $index => $idUsuarioSeleccionado) {
            $usuario = $usuariosPorId[$idUsuarioSeleccionado];
            $usuario->resetPlanes();

            $planUsuario = new \App\Entity\PlanUsuario();
            $planUsuario->setUsuario($usuario);
            $planUsuario->setPlan($plan);
            if ($fechaInicio instanceof \DateTimeInterface) {
                $planUsuario->setFechaInicio(\DateTime::createFromInterface($fechaInicio));
            }
            $planUsuario->setPredefinido(true);
            $planUsuario->setContabilizaIngreso($index === 0);
            $planUsuario->setGrupoCompartido($grupoCompartido);
            $planUsuario->setOrdenBeneficiario($index + 1);
            $planUsuario->setTotalBeneficiariosGrupo($totalBeneficiariosGrupo);

            $planUsuarioRepository->save($planUsuario, $index === ($totalBeneficiariosGrupo - 1));
        }

        if ($totalBeneficiariosGrupo > 1) {
            return new JsonResponse([
                'status' => 'success',
                'message' => sprintf(
                    'Plan compartido vinculado a %d usuarios. Se contabilizo una sola venta para reportes.',
                    $totalBeneficiariosGrupo
                )
            ]);
        }

        return new JsonResponse(['status' => 'success', 'message' => 'Plan vinculado al usuario exitosamente.']);
    }


}
