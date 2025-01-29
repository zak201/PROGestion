<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use App\Service\StatisticsService;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private StatisticsService $statisticsService
    ) {}

    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        $this->logger->info('Page d\'accueil visitée');
        try {
            $stats = $this->statisticsService->getGlobalStatistics();
            $this->logger->info('Statistiques récupérées', ['stats' => $stats]);
            
            return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors du rendu de la page d\'accueil', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
