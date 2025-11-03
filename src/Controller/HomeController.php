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
use App\Repository\DatoFisicoRepository;
use App\Repository\ColaCardsRepository;
use App\Entity\Usuario;







final class HomeController extends AbstractController
{
    //crear contructor
    public function __construct(
        private CardRepository $cardRepository,
        private CardsRepository $cardsRepository,
        private UsuarioRepository $userRepository,
        private DatoFisicoRepository $datofisicoRepository,
        private ColaCardsRepository $colaCardsRepository
    ) {}

    #[Route('/ingreso', name: 'app_ingreso')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/update_identificador', name: 'app_home_updated')]
    public function app_home_updated(Request $request): JsonResponse
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

        //validar si hay una card por usuario y esta activa
        $card = $this->cardRepository->getFirstCard();
        if ($card->getCode() == $code) {
            return new JsonResponse(["status" => "error", 'message' => 'Identificador ya registrado'], 200);
        }

        //buscar una colaCards por code
        $colaCards = $this->colaCardsRepository->findOneBy(['code' => $code, 'active' => 1]);

        //si no se encuentra la colaCards se agrega
        if (!$colaCards instanceof \App\Entity\ColaCards) {
            $colaCards = new \App\Entity\ColaCards();
            $colaCards->setCode($cards->getCode());
            $colaCards->setUsuario($cards->getUsuario());
            $this->colaCardsRepository->save($colaCards);
        }
        $card->setCode($cards->getCode());
        $card->setUsuario($cards->getUsuario()->getCedula());
        $this->cardRepository->save($card, true);
        return new JsonResponse(['status' => 'success', 'message' => 'Identificador verificado correctamente'], 200);
    }


    #[Route('/update_identificador', name: 'app_home_updated_dash')]
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

    #[Route('/sse', name: 'sse')]
    public function sse(): StreamedResponse
    {
        return new StreamedResponse(function () {
            // 🔧 Headers SSE
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            $ultimoId = null;
            while (true) {
                // limpiar el EM para evitar cacheo
                $this->cardRepository->clear();
                $card = $this->cardRepository->getLastCard();
                if ($card instanceof Card && $card->getCode() !== $ultimoId) {
                    // buscar usuario por cédula
                    $usuario = $this->userRepository->findOneBy(['cedula' => $card->getUsuario()]);
                    // obtener todas las cards activas en array
                    $arrayCards = array_map(fn($c) => $c->toArray(), $this->colaCardsRepository->getAllCardsActive());

                    // si hay usuario, añadir sus datos + cards
                    $payload = $usuario instanceof Usuario
                        ? array_merge($usuario->toArray(), ['cards' => $arrayCards])
                        : [];

                    echo "data: " . json_encode($payload) . "\n\n";
                    flush();

                    $ultimoId = $card->getCode();
                }

                sleep(1);
            }
        });
    }
}
