<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Repository\IdTempRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\IdTemp;



final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/update_identificador', name: 'app_home_updated')]
    public function app_home_updated(IdTempRepository $idTempRepository, Request $request): JsonResponse
    {
        $entity = $idTempRepository->find(1);
        $newId = $request->request->get('id');
        $entity->setIdentificador($newId);
        if (!$entity instanceof IdTemp) {
            return new JsonResponse(['status' => 'error', 'message' => 'Entidad no encontrada'], 404);
        }
        $idTempRepository->save($entity, true);
        return new JsonResponse(['status' => 'ok']);
    }


    #[Route('/sse', name: 'sse')]
    public function sse(IdTempRepository $idTempRepository): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($idTempRepository) {
            // 🔧 Headers SSE
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');

            // 🔧 Datos simulados
            $empty = [
                'id' => 0,
                'name' => 'No encontrado',
                'accion' => 'desconocida',
                'imageUrl' => 'img/default.jpeg'
            ];

            $datos = [
                ['id' => 1, 'name' => 'Carlos Carolina', 'accion' => 'ingreso', 'imageUrl' => 'img/profile-img.jpeg'],
                ['id' => 2, 'name' => 'Diana Carolina', 'accion' => 'ingreso', 'imageUrl' => 'img/profile-img.jpeg'],
                ['id' => 3, 'name' => 'Carolina', 'accion' => 'ingreso', 'imageUrl' => 'img/profile-img.jpeg'],
                ['id' => 4, 'name' => 'Pedro Carolina', 'accion' => 'ingreso', 'imageUrl' => 'img/profile-img.jpeg']
            ];

            $ultimoId = null;
            while (true) {
                $id = $idTempRepository->getIdentificador();
                if ($id !== $ultimoId) {
                    $data = array_values(array_filter($datos, fn($d) => $d['id'] === $id))[0] ?? $empty;
                    echo "data: " . json_encode($data) . "\n\n";
                    flush();
                    $ultimoId = $id;
                }

                sleep(1); // 0.5 segundos
            }
        });
        return $response;
    }
}
