<?php

namespace App\Controller;

use App\Service\StatisticsService;
use App\Service\ChartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Psr\Log\LoggerInterface;

#[IsGranted('ROLE_USER')]
class DashboardController extends AbstractController
{
    public function __construct(
        private StatisticsService $statisticsService,
        private ChartService $chartService,
        private LoggerInterface $logger
    ) {}

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        try {
            $stats = $this->statisticsService->getGlobalStatistics();
            $charts = $this->chartService->generateDashboardCharts();

            $this->logger->info('Accès au tableau de bord', [
                'user' => $this->getUser()->getUserIdentifier()
            ]);

            return $this->render('dashboard/index.html.twig', [
                'stats' => $stats,
                'charts' => $charts
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Erreur sur le tableau de bord', [
                'error' => $e->getMessage()
            ]);
            $this->addFlash('error', 'Erreur lors du chargement du tableau de bord');
            return $this->redirectToRoute('app_home');
        }
    }

    #[Route('/dashboard/export', name: 'app_dashboard_export')]
    #[IsGranted('ROLE_ADMIN')]
    public function export(): Response
    {
        try {
            $export = $this->statisticsService->generateExport();
            
            return $this->file($export, 'dashboard-export.xlsx');
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'export', [
                'error' => $e->getMessage()
            ]);
            $this->addFlash('error', 'Erreur lors de l\'export');
            return $this->redirectToRoute('app_dashboard');
        }
    }
} 