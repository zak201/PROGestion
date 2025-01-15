<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'cards' => [
                ['title' => 'Lots', 'route' => 'app_lots'],
                ['title' => 'Véhicules', 'route' => 'app_vehicules'],
                ['title' => 'Avaries', 'route' => 'app_avaries'],
            ],
        ]);
    }
}
