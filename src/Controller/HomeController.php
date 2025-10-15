<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Doctrine\DBAL\Connection;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/sse', name: 'sse')]
    public function sse(): StreamedResponse
    {
        return new StreamedResponse(function () {
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');

            while (true) {
                $data = [
                    'timestamp' => date('Y-m-d H:i:s'),
                    'usuario' => 'diana',
                    'accion' => 'ingreso',
                ];

                echo "data: " . json_encode($data) . "\n\n";
                ob_flush();
                flush();
                //sleep(50); // medio segundo
            }
        });
    }
}
