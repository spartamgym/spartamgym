<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Repository\CardRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Card;
use App\Entity\Cards;
use App\Repository\CardsRepository;
use App\Repository\UsuarioRepository;
use App\Repository\ColaCardsRepository;
use App\Entity\Usuario;



final class HomeController extends AbstractController
{
    //crear contructor
    public function __construct(
        private CardRepository $cardRepository,
        private CardsRepository $cardsRepository,
        private UsuarioRepository $userRepository,
        private ColaCardsRepository $colaCardsRepository
    ) {}

    #[Route('/ingreso', name: 'app_ingreso')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
    #[Route('/salida', name: 'app_salida')]
    public function salida(): Response
    {
        return $this->render('home/salida.html.twig');
    }

    #[Route('/update_identificador_salida', name: 'app_update_identificador_salida')]
    public function update_identificador_salida(Request $request): JsonResponse
    {
        //validar que venga el id
        if (!$request->request->has('id')) {
            return new JsonResponse(['message' => 'Falta el code'], 400);
        }
        $code = $request->request->get('id');

        //buscar una colaCards por code
        $colaCards = $this->colaCardsRepository->findOneBy(['code' => $code, 'ingreso' => 1]);
        //si no se encuentra la colaCards se agrega
        if (!$colaCards instanceof \App\Entity\ColaCards) {
            return new JsonResponse(["status" => "error", 'message' => 'usuario ya salio'], 200);
        }

        $this->colaCardsRepository->save($colaCards);
        return new JsonResponse(['status' => 'success', 'message' => 'regresa pronto' . $colaCards->getUsuario()->getNombre()], 200);
    }

    #[Route('/update_identificador_ingreso', name: 'app_home_updated')]
    public function app_home_updated(Request $request): JsonResponse
    {

        //validar que venga el id
        if (!$request->request->has('id')) {
            return new JsonResponse(['message' => 'Falta el code'], 400);
        }
        $code = $request->request->get('id');

        //validar si hay una cards por code
        $cards = $this->cardsRepository->findOneBy(['code' => $code]);

        if (!$cards instanceof Cards) {
            return new JsonResponse(["status" => "error", 'message' => 'usuario no registrado'], 200);
        }

        if (!$cards->getUsuario()->isPlanVigente()) {
            return new JsonResponse(["status" => "error", 'message' => 'usuario no tiene plan vigente o predefinido'], 200);
        }

        //validar si hay una card por usuario y esta activa

        if ($cards->getUsuario()->hasColaCards()) {
            return new JsonResponse(["status" => "error", 'message' => 'usuario ya ingreso'], 200);
        }

        //buscar una colaCards por code
        $colaCards = $this->colaCardsRepository->findOneBy(['code' => $code, 'ingreso' => 1]);
        //si no se encuentra la colaCards se agrega
        if ($colaCards instanceof \App\Entity\ColaCards) {
            return new JsonResponse(["status" => "error", 'message' => 'usuario ya ingreso'], 200);
        }

        $colaCards = new \App\Entity\ColaCards();
        $colaCards->setCode($cards->getCode());
        $colaCards->setUsuario($cards->getUsuario());
        $this->colaCardsRepository->save($colaCards);

        $card = $this->cardRepository->getFirstCard();
        $card->setCode($cards->getCode());
        $card->setUsuario($cards->getUsuario()->getCedula());
        $this->cardRepository->save($card, true);
        return new JsonResponse(['status' => 'success', 'message' => 'Bienvenido al sistema ' . $cards->getUsuario()->getNombre()], 200);
    }


    #[Route('/update_identificador_dash', name: 'app_home_updated_dash')]
    public function app_home_updated_dash(Request $request): JsonResponse
    {
        //validar que venga el id
        if (!$request->request->has('id')) {
            return new JsonResponse(['message' => 'Falta el id'], 400);
        }
        $code = $request->request->get('id');

        //validar si hay una cards por code
        $cards = $this->cardsRepository->findOneBy(['code' => $code]);

        if (!$cards instanceof Cards) {
            return new JsonResponse(["status" => "error", 'message' => 'usuario no registrado'], 200);
        }
        $card = $this->cardRepository->findOneBy([], ['id' => 'ASC']);

        if (!$card instanceof Card) {
            $card = new Card();
        }

        $card->setCode($cards->getCode());
        $card->setUsuario($cards->getUsuario()->getCedula());

        $this->cardRepository->save($card, true);
        return new JsonResponse(['status' => 'success', 'message' => 'Identificador verificado correctamente'], 200);
    }

    #[Route('/update_identificador_dash_cedula', name: 'app_home_updated_dash_cedula')]
    public function app_home_updated_dash_cedula(Request $request): JsonResponse
    {

        //validar que venga el id
        if (!$request->request->has('id')) {
            return new JsonResponse(['message' => 'Falta el id'], 400);
        }
        $code = $request->request->get('id');

        //validar si hay una cards por code

        $card = $this->cardRepository->findOneBy([], ['id' => 'ASC']);

        if (!$card instanceof Card) {
            $card = new Card();
        }

        $card->setCode($code);
        $card->setUsuario($code);

        $this->cardRepository->save($card);
        return new JsonResponse(['status' => 'success', 'message' => 'Identificador verificado correctamente'], 200);
    }

    #[Route('/sse', name: 'sse')]
    public function sse(): StreamedResponse
    {
        $response = new StreamedResponse(function () {
            $ultimoId = null;
            
            // Bucle limitado para evitar bloqueos perpetuos si algo falla
            for ($i = 0; $i < 600; $i++) { // Max 10 minutos por conexión
                // Limpiar el EM para obtener datos frescos de la DB
                $this->cardRepository->clear();
                $card = $this->cardRepository->getFirstCard();

                if ($card instanceof Card && $card->getCode() !== $ultimoId) {
                    $usuario = $this->userRepository->findOneBy(['cedula' => $card->getUsuario()]);
                    $arrayCards = array_map(fn($c) => $c->toArray(), $this->colaCardsRepository->getAllCardsActive());

                    $payload = $usuario instanceof Usuario
                        ? array_merge($usuario->toArray(), ['cards' => $arrayCards])
                        : ['cards' => $arrayCards, 'no-user' => true];

                    echo "data: " . json_encode($payload) . "\n\n";
                    ob_flush();
                    flush();
                    $ultimoId = $card->getCode();
                } else {
                    // Si no hay tarjeta o es la misma, enviamos un keep-alive opcional o simplemente esperamos
                    echo ": keep-alive\n\n";
                    ob_flush();
                    flush();
                }

                if (connection_aborted()) {
                    break;
                }

                sleep(2); // Aumentamos un poco el delay para reducir carga
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no'); // Desactivar buffering en proxies

        return $response;
    }
}
