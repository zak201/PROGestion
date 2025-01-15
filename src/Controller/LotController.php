<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LotController extends AbstractController
{
    #[Route('/lots', name: 'app_lots')]
    public function index(): Response
    {
        return $this->render('lot/index.html.twig', [
            'controller_name' => 'LotController',
        ]);
    }
}
