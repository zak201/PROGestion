<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvarieController extends AbstractController
{
    #[Route('/avaries', name: 'app_avaries')]
    public function index(): Response
    {
        return $this->render('avarie/index.html.twig', [
            'controller_name' => 'AvarieController',
        ]);
    }
}
