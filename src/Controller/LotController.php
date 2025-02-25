<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LotRepository;
use App\Entity\Lot;
use App\Form\LotType;
use App\Service\LotService;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\DTO\LotFormData;
use App\Repository\VehiculeRepository;
use App\Repository\CamionRepository;
use App\Form\LotEditType;
use Doctrine\Common\Collections\ArrayCollection;

class LotController extends AbstractController
{
    public function __construct(
        private LotService $lotService,
        private LoggerInterface $logger,
        private EntityManagerInterface $entityManager,
        private VehiculeRepository $vehiculeRepository,
        private CamionRepository $camionRepository
    ) {}

    #[Route('/lots', name: 'app_lots')]
    public function index(Request $request): Response
    {
        try {
            $page = $request->query->getInt('page', 1);
            $filters = $request->query->all('filter');

            $lots = $this->lotService->getPaginatedLots($page, $filters);
            $stats = $this->lotService->getLotStats();

            return $this->render('lot/index.html.twig', [
                'lots' => $lots,
                'stats' => $stats,
                'filters' => $filters,
                'currentWeek' => (new \DateTime())->format('W'),
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'affichage des lots', [
                'error' => $e->getMessage(),
            ]);
            $this->addFlash('error', 'Une erreur est survenue lors du chargement des lots');
            return $this->redirectToRoute('app_home');
        }
    }

    #[Route('/new', name: 'app_lot_new')]
    public function new(Request $request): Response
    {
        $formData = new LotFormData();
        $form = $this->createForm(LotType::class, $formData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $lot = new Lot();
                $lot->setNumeroLot($formData->numeroLot);
                $lot->setDateExpedition($formData->dateExpedition);

                // Gestion des véhicules
                $vehiculeIds = array_map('trim', preg_split('/[\s,]+/', $formData->vehiculesInput));
                foreach ($vehiculeIds as $vehiculeId) {
                    if (!empty($vehiculeId)) {
                        $vehicule = $this->vehiculeRepository->findOneBy(['numeroChassis' => $vehiculeId]);
                        if (!$vehicule) {
                            throw new \Exception("Véhicule non trouvé : $vehiculeId");
                        }
                        $lot->addVehicule($vehicule);
                    }
                }

                // Gestion du camion
                if (!empty($formData->camionInput)) {
                    $camion = $this->camionRepository->findOneBy([
                        'immatriculation' => trim($formData->camionInput)
                    ]);
                    
                    if ($camion) {
                        $lot->setCamion($camion);
                        $camion->addLot($lot); // Assurer la relation bidirectionnelle
                        $this->entityManager->persist($camion);
                    } else {
                        $this->addFlash('warning', "Camion non trouvé : {$formData->camionInput}");
                    }
                }

                $this->entityManager->persist($lot);
                $this->entityManager->flush();

                $this->addFlash('success', 'Lot créé avec succès');
                return $this->redirectToRoute('app_lots');
            } catch (\Exception $e) {
                $this->logger->error('Erreur lors de la création du lot', [
                    'error' => $e->getMessage(),
                    'data' => [
                        'numero_lot' => $formData->numeroLot,
                        'camion_input' => $formData->camionInput
                    ]
                ]);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('lot/new.html.twig', [
            'form' => $form->createView(),
            'weekNumber' => (new \DateTime())->format('W'),
        ]);
    }

    #[Route('/lot/{id}', name: 'app_lot_show')]
    public function show(Lot $lot): Response
    {
        return $this->render('lot/show.html.twig', [
            'lot' => $lot,
        ]);
    }

    #[Route('/lots/{id}/edit', name: 'app_lot_edit')]
    public function edit(Request $request, Lot $lot): Response
    {
        $form = $this->createForm(LotEditType::class, $lot, [
            'vehicules_numeros' => $lot->getVehicules()->map(fn($v) => $v->getNumeroChassis())->toArray(),
            'camion_immatriculation' => $lot->getCamion() ? $lot->getCamion()->getImmatriculation() : null,
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Gestion des véhicules
                $vehiculesInput = $form->get('vehiculesInput')->getData();
                $newVehicules = new ArrayCollection();
                $errors = [];

                if (!empty($vehiculesInput)) {
                    $vehiculeIds = array_map('trim', preg_split('/[\s,]+/', $vehiculesInput));
                    foreach ($vehiculeIds as $numeroChassis) {
                        if (!empty($numeroChassis)) {
                            $vehicule = $this->vehiculeRepository->findOneBy(['numeroChassis' => $numeroChassis]);
                            if ($vehicule) {
                                $newVehicules->add($vehicule);
                            } else {
                                $errors[] = "Véhicule non trouvé : $numeroChassis";
                            }
                        }
                    }
                }

                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        $this->addFlash('warning', $error);
                    }
                    return $this->render('lot/edit.html.twig', [
                        'form' => $form->createView(),
                        'lot' => $lot,
                        'weekNumber' => (new \DateTime())->format('W'),
                    ]);
                }

                // Mise à jour des véhicules
                foreach ($lot->getVehicules() as $vehicule) {
                    $lot->removeVehicule($vehicule);
                }
                foreach ($newVehicules as $vehicule) {
                    $lot->addVehicule($vehicule);
                }

                // Gestion du camion
                $camionInput = $form->get('camionInput')->getData();
                if ($camionInput) {
                    $camion = $this->camionRepository->findOneBy(['immatriculation' => trim($camionInput)]);
                    if ($camion) {
                        $lot->setCamion($camion);
                    } else {
                        $this->addFlash('warning', "Camion non trouvé : $camionInput");
                    }
                } else {
                    $lot->setCamion(null);
                }

                $this->entityManager->flush();
                $this->addFlash('success', 'Lot modifié avec succès');
                return $this->redirectToRoute('app_lots');

            } catch (\Exception $e) {
                $this->logger->error('Erreur lors de la modification du lot', [
                    'error' => $e->getMessage(),
                    'lot_id' => $lot->getId()
                ]);
                $this->addFlash('error', 'Une erreur est survenue lors de la modification.');
            }
        }

        return $this->render('lot/edit.html.twig', [
            'form' => $form->createView(),
            'lot' => $lot,
            'weekNumber' => (new \DateTime())->format('W'),
        ]);
    }

    #[Route('/lots/{id}/delete', name: 'app_lot_delete', methods: ['POST'])]
    public function delete(Request $request, Lot $lot): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lot->getId(), $request->request->get('_token'))) {
            try {
                // Détacher les véhicules avant la suppression
                foreach ($lot->getVehicules() as $vehicule) {
                    $vehicule->setLot(null);
                    $this->entityManager->persist($vehicule);
                }
                
                $this->entityManager->remove($lot);
                $this->entityManager->flush();
                
                $this->addFlash('success', 'Lot supprimé avec succès');
            } catch (\Exception $e) {
                $this->logger->error('Erreur lors de la suppression du lot', [
                    'error' => $e->getMessage(),
                    'lot_id' => $lot->getId()
                ]);
                $this->addFlash('error', 'Une erreur est survenue lors de la suppression du lot');
            }
        }

        return $this->redirectToRoute('app_lots');
    }
}
