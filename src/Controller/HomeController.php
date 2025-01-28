<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Psr\Log\LoggerInterface;
use App\Service\StatisticsService;

class HomeController extends AbstractController
{
    public function __construct(
        private LoggerInterface $logger,
        private StatisticsService $statisticsService
    ) {}

    #[Route('/', name: 'app_home')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $stats = $this->statisticsService->getGlobalStatistics();
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'stats' => $stats
        ]);
    }
}
