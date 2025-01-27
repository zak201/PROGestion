<?php

namespace App\Controller;

use App\Repository\VehiculeRepository;
use App\Repository\LotRepository;
use App\Repository\AvarieRepository;
use App\Repository\CamionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    public function __construct(
        private VehiculeRepository $vehiculeRepository,
        private LotRepository $lotRepository,
        private AvarieRepository $avarieRepository,
        private CamionRepository $camionRepository
    ) {}

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        $stats = [
            'vehicules' => $this->vehiculeRepository->countByStatus(),
            'lots' => $this->lotRepository->countActive(),
            'avaries' => $this->avarieRepository->countNonResolved(),
            'camions' => $this->camionRepository->countAvailable()
        ];

        return $this->render('dashboard/index.html.twig', [
            'stats' => $stats
        ]);
    }
} 