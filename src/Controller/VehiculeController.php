<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Form\VehiculeType;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\VehiculeService;
use App\Service\VehiculeManager;
use Symfony\Component\Security\Core\Annotation\IsGranted;


#[Route('/vehicules', name: 'app_vehicule_')]
class VehiculeController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(VehiculeService $vehiculeService): Response
    {
        return $this->render('vehicule/index.html.twig', [
            'vehicules' => $vehiculeService->getVehiculesDisponibles()
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, VehiculeManager $vehiculeManager): Response
    {
        $vehicule = new Vehicule();
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vehiculeManager->createVehicule($vehicule);
            $this->addFlash('success', 'Véhicule créé avec succès');
            return $this->redirectToRoute('app_vehicule_index');
        }

        return $this->render('vehicule/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Vehicule $vehicule): Response
    {
        return $this->render('vehicule/show.html.twig', compact('vehicule'));
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vehicule $vehicule, VehiculeManager $vehiculeManager): Response
    {
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vehiculeManager->updateStatus($vehicule, $form->get('status')->getData());
            $this->addFlash('success', 'Véhicule mis à jour');
            return $this->redirectToRoute('app_vehicule_index');
        }

        return $this->render('vehicule/edit.html.twig', [
            'form' => $form->createView(),
            'vehicule' => $vehicule
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vehicule->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vehicule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vehicule_index');
    }
}
