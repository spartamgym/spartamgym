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
use App\Repository\DatofisicoRepository;





final class HomeController extends AbstractController
{
    //crear contructor
    public function __construct(
        private CardRepository $cardRepository,
        private CardsRepository $cardsRepository,
        private UsuarioRepository $userRepository,
        private DatofisicoRepository $datofisicoRepository
    ) {}

    #[Route('/', name: 'app_home')]
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
            return new JsonResponse([ 'message' => 'usted no esta registrado'], 200);
        }

        $card = $this->cardRepository->find(1);
        if (!$card instanceof Card) {
            return new JsonResponse(['message' => 'Entidad no encontrada'], 404);
        }
        $card->setCode($cards->getCode());

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
                $id = $this->cardRepository->getIdentificador();
                if ($id !== $ultimoId) {
                    $data = $this->userRepository->findOneBy(['code' => $id]);
                    echo "data: " . json_encode($data->toArray()) . "\n\n";
                    flush();
                    $ultimoId = $id;
                }
                sleep(1); // 0.5 segundos
            }
        });
    }
}
