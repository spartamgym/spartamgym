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
use App\Entity\Usuario;





final class HomeController extends AbstractController
{
    //crear contructor
    public function __construct(
        private CardRepository $cardRepository,
        private CardsRepository $cardsRepository,
        private UsuarioRepository $userRepository,
        private DatoFisicoRepository $datofisicoRepository
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
            return new JsonResponse(["status"=>"error",'message' => 'usuario no registrado'], 200);
        }
        $card = $this->cardRepository->findOneBy([], ['id' => 'ASC']);

        if (!$card instanceof Card) {
            $card = new Card();
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
              
             // Reintentar cada 1 segundo si la conexión se pierde
                $card = $this->cardRepository->findOneBy([], ['id' => 'ASC']);
                if ($card->getId() !== $ultimoId) {
                    //buscar cards por code
                    $cards = $this->cardsRepository->findOneBy(['code' => $card->getCode()]);
                    $data = $this->userRepository->findOneBy(['card' => $cards]);
                    // Enviar datos al cliente si es null enviar array vacio
                    echo "data: " . json_encode($data instanceof Usuario ? $data->toArray() : []) . "\n\n";
                    flush();
                    $ultimoId = $card->getId();
                }
                sleep(1); // 0.5 segundos
            }
        });
    }
}
