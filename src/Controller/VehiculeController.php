<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Repository\VehiculeRepository;
use App\Entity\Vehicule;
use App\Service\VehiculeService;
use App\Form\VehiculeType;
use App\DTO\VehiculeDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Psr\Log\LoggerInterface;
use App\Exception\VehiculeNotFoundException;

class VehiculeController extends AbstractController
{
    public function __construct(
        private VehiculeService $vehiculeService,
        private LoggerInterface $logger
    ) {}

    #[Route('/vehicules', name: 'app_vehicules')]
    public function index(Request $request): Response
    {
        try {
            $page = $request->query->getInt('page', 1);
            $filters = $request->query->all('filter');

            $pagination = $this->vehiculeService->getPaginatedVehicules($page, $filters);
            $stats = $this->vehiculeService->getVehiculeStats();

            return $this->render('vehicule/index.html.twig', [
                'pagination' => $pagination,
                'stats' => $stats,
                'filters' => $filters
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'affichage des véhicules', [
                'error' => $e->getMessage()
            ]);
            $this->addFlash('error', 'Une erreur est survenue lors du chargement des véhicules');
            return $this->redirectToRoute('app_home');
        }
    }

    #[Route('/vehicule/new', name: 'app_vehicule_new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request): Response
    {
        $form = $this->createForm(VehiculeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $vehicule = $this->vehiculeService->createVehicule($form->getData());
                $this->addFlash('success', 'Véhicule créé avec succès');
                return $this->redirectToRoute('app_vehicule_show', ['id' => $vehicule->getId()]);
            } catch (\Exception $e) {
                $this->logger->error('Erreur lors de la création du véhicule', [
                    'error' => $e->getMessage(),
                    'data' => $form->getData()
                ]);
                $this->addFlash('error', 'Erreur lors de la création du véhicule');
            }
        }

        return $this->render('vehicule/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/vehicule/{id}', name: 'app_vehicule_show')]
    public function show(int $id): Response
    {
        try {
            $vehicule = $this->vehiculeService->getVehiculeById($id);
            $dto = VehiculeDTO::fromEntity($vehicule);

            return $this->render('vehicule/show.html.twig', [
                'vehicule' => $dto
            ]);
        } catch (VehiculeNotFoundException $e) {
            $this->logger->warning('Véhicule non trouvé', ['id' => $id]);
            throw $this->createNotFoundException('Le véhicule demandé n\'existe pas');
        }
    }

}
