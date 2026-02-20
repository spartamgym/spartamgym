<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Cards;
use App\Repository\CardsRepository;
use App\Repository\UsuarioRepository;
use App\Repository\ColaCardsRepository;
use App\Entity\Usuario;
use App\Entity\ColaCards;
use App\Service\DashboardRealtimePublisher;



final class HomeController extends AbstractController
{
    //crear contructor
    public function __construct(
        private CardsRepository $cardsRepository,
        private UsuarioRepository $userRepository,
        private ColaCardsRepository $colaCardsRepository,
        private DashboardRealtimePublisher $dashboardRealtimePublisher
    ) {}

    #[Route('/ingreso', name: 'app_ingreso')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
    #[Route('/salida', name: 'app_salida')]
    public function salida(): Response
    {
        // Vista unificada: salida usa el mismo flujo visual de acceso.
        return $this->redirectToRoute('app_ingreso');
    }

    #[Route('/update_identificador_salida', name: 'app_update_identificador_salida')]
    public function update_identificador_salida(Request $request): JsonResponse
    {
        $code = $this->extractCodeFromRequest($request);
        if ($code === null) {
            return new JsonResponse(['message' => 'Falta el code'], 400);
        }

        // buscar sesion abierta por code
        $colaCards = $this->colaCardsRepository->findOpenByCode($code);
        if (!$colaCards instanceof ColaCards) {
            return new JsonResponse(["status" => "error", 'message' => 'usuario no tiene un ingreso activo'], 200);
        }

        return $this->closeOpenSession($colaCards, 'manual_salida');
    }

    #[Route('/update_identificador_movimiento', name: 'app_update_identificador_movimiento')]
    public function update_identificador_movimiento(Request $request): JsonResponse
    {
        $code = $this->extractCodeFromRequest($request);
        if ($code === null) {
            return new JsonResponse(['message' => 'Falta el code'], 400);
        }

        // 1) Si tiene sesion abierta con este mismo code, decide salida o cierre de sesion vieja.
        $openByCode = $this->colaCardsRepository->findOpenByCode($code);
        if ($openByCode instanceof ColaCards) {
            if ($this->isSessionFromPreviousDay($openByCode)) {
                // Sesion vieja: se cierra y se permite nuevo ingreso.
                $openByCode->setIngreso(false);
                $openByCode->setVerificado(true);
                $this->colaCardsRepository->save($openByCode);
            } else {
                return $this->closeOpenSession($openByCode, 'movimiento_toggle');
            }
        }

        // 2) Resolver usuario por tarjeta.
        $cards = $this->cardsRepository->findOneBy(['code' => $code]);
        if (!$cards instanceof Cards) {
            return new JsonResponse(["status" => "error", 'message' => 'usuario no registrado'], 200);
        }

        $usuario = $cards->getUsuario();
        if (!$usuario instanceof Usuario) {
            return new JsonResponse(["status" => "error", 'message' => 'tarjeta sin usuario asignado'], 200);
        }

        // 3) Si el usuario tiene sesion abierta activa hoy, se interpreta como salida.
        $openByUsuario = $this->colaCardsRepository->findOpenByUsuario($usuario);
        if ($openByUsuario instanceof ColaCards) {
            if ($this->isSessionFromPreviousDay($openByUsuario)) {
                $openByUsuario->setIngreso(false);
                $openByUsuario->setVerificado(true);
                $this->colaCardsRepository->save($openByUsuario);
            } else {
                return $this->closeOpenSession($openByUsuario, 'movimiento_toggle');
            }
        }

        // 4) Si no estaba adentro, solo puede entrar si tiene plan vigente.
        if (!$usuario->isPlanVigente()) {
            return new JsonResponse(["status" => "error", 'message' => 'usuario no tiene plan vigente'], 200);
        }

        $colaCards = new ColaCards();
        $colaCards->setCode($cards->getCode());
        $colaCards->setUsuario($usuario);
        $colaCards->setIngreso(true);
        $colaCards->setVerificado(false);
        $this->colaCardsRepository->save($colaCards);
        $this->dashboardRealtimePublisher->publishQueueUpdated();
        $this->dashboardRealtimePublisher->publishUserSelected($usuario, 'ingreso');

        return new JsonResponse([
            'status' => 'success',
            'action' => 'ingreso',
            'message' => 'Bienvenido al sistema ' . $usuario->getNombre()
        ], 200);
    }

    #[Route('/update_identificador_ingreso', name: 'app_home_updated')]
    public function app_home_updated(Request $request): JsonResponse
    {
        // Compatibilidad: ruta legacy de ingreso ahora usa flujo unificado.
        return $this->update_identificador_movimiento($request);
    }


    #[Route('/update_identificador_dash', name: 'app_home_updated_dash')]
    public function app_home_updated_dash(Request $request): JsonResponse
    {
        //validar que venga el id
        if (!$request->request->has('id')) {
            return new JsonResponse(['message' => 'Falta el id'], 400);
        }
        $code = $request->request->get('id');

        $colaCardsOpen = $this->colaCardsRepository->findOpenByCode($code);
        if (!$colaCardsOpen instanceof \App\Entity\ColaCards) {
            return new JsonResponse(["status" => "error", 'message' => 'identificador no encontrado en cola activa'], 200);
        }

        $usuario = $colaCardsOpen->getUsuario();
        if (!$usuario instanceof Usuario) {
            return new JsonResponse(["status" => "error", 'message' => 'tarjeta sin usuario asignado'], 200);
        }

        $this->dashboardRealtimePublisher->publishUserSelected($usuario, 'dashboard_queue');
        return new JsonResponse([
            'status' => 'success',
            'message' => 'Usuario cargado desde la cola.',
            'user' => $usuario->toArray(),
        ], 200);
    }

    #[Route('/update_identificador_dash_cedula', name: 'app_home_updated_dash_cedula')]
    public function app_home_updated_dash_cedula(Request $request): JsonResponse
    {

        //validar que venga el id
        if (!$request->request->has('id')) {
            return new JsonResponse(['message' => 'Falta el id'], 400);
        }
        $code = $request->request->get('id');

        $usuario = $this->userRepository->findOneBy(['cedula' => $code]);
        if (!$usuario instanceof Usuario) {
            return new JsonResponse(['status' => 'error', 'message' => 'Usuario no encontrado por cédula.'], 200);
        }

        $this->dashboardRealtimePublisher->publishUserSelected($usuario, 'dashboard_search');
        return new JsonResponse([
            'status' => 'success',
            'message' => 'Identificador verificado correctamente',
            'user' => $usuario->toArray(),
        ], 200);
    }

    private function extractCodeFromRequest(Request $request): ?string
    {
        $code = trim((string)$request->request->get('id', ''));
        return $code !== '' ? $code : null;
    }

    private function isSessionFromPreviousDay(ColaCards $colaCards): bool
    {
        $openAt = $colaCards->getCreateAt();
        if (!$openAt instanceof \DateTimeInterface) {
            return false;
        }

        $today = new \DateTimeImmutable('today');
        return $openAt < $today;
    }

    private function closeOpenSession(ColaCards $colaCards, string $source): JsonResponse
    {
        $colaCards->setIngreso(false);
        $colaCards->setVerificado(true);
        $this->colaCardsRepository->save($colaCards);
        $this->dashboardRealtimePublisher->publishQueueUpdated();

        $usuario = $colaCards->getUsuario();
        if ($usuario instanceof Usuario) {
            $this->dashboardRealtimePublisher->publishUserSelected($usuario, 'salida');
        }

        return new JsonResponse([
            'status' => 'success',
            'action' => 'salida',
            'source' => $source,
            'message' => 'Regresa pronto ' . ($usuario?->getNombre() ?? ''),
        ], 200);
    }
}
