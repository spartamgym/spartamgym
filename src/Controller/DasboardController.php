<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DasboardController extends AbstractController
{
    #[Route('/dasboard', name: 'app_dasboard')]
    public function index(): Response
    {
        return $this->render('dasboard/index.html.twig', [
            'controller_name' => 'DasboardController',
        ]);
    }
}
