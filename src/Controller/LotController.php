<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Repository\LotRepository;
use App\Entity\Lot;
use App\Form\LotType;
use App\Service\LotService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LotController extends AbstractController
{
    public function __construct(
        private LotService $lotService,
        private LoggerInterface $logger
    ) {}

    #[Route('/lots', name: 'app_lots')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request): Response
    {
        try {
            $page = $request->query->getInt('page', 1);
            $filters = $request->query->all('filter');

            $pagination = $this->lotService->getPaginatedLots($page, $filters);
            $stats = $this->lotService->getLotStats();

            return $this->render('lot/index.html.twig', [
                'pagination' => $pagination,
                'stats' => $stats,
                'filters' => $filters
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'affichage des lots', [
                'error' => $e->getMessage()
            ]);
            $this->addFlash('error', 'Une erreur est survenue lors du chargement des lots');
            return $this->redirectToRoute('app_home');
        }
    }

    #[Route('/new', name: 'app_lot_new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request): Response
    {
        $form = $this->createForm(LotType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $lot = $this->lotService->createLot($form->getData());
                $this->addFlash('success', 'Lot créé avec succès');
                return $this->redirectToRoute('app_lot_show', ['id' => $lot->getId()]);
            } catch (\Exception $e) {
                $this->logger->error('Erreur lors de la création du lot', [
                    'error' => $e->getMessage(),
                    'data' => $form->getData()
                ]);
                $this->addFlash('error', 'Erreur lors de la création du lot');
            }
        }

        return $this->render('lot/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'app_lot_show')]
    public function show(Lot $lot): Response
    {
        return $this->render('lot/show.html.twig', [
            'lot' => $lot
        ]);
    }

    #[Route('/lot', name: 'app_lot_index')]
    public function index(): Response
    {
        return $this->render('lot/index.html.twig');
    }
}
