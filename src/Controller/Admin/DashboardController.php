<?php

namespace App\Controller\Admin;

use App\Entity\Avarie;
use App\Entity\Lot;
use App\Entity\Utilisateur;
use App\Entity\Vehicule;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class DashboardController extends AbstractDashboardController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Utilisation de l'EntityManager au lieu de getDoctrine()
        $stats = [
            'vehicules' => $this->entityManager->getRepository(Vehicule::class)->count([]),
            'lots' => $this->entityManager->getRepository(Lot::class)->count([]),
            'avaries' => $this->entityManager->getRepository(Avarie::class)->count([]),
        ];

        return $this->render('admin/dashboard.html.twig', [
            'stats' => $stats
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('PROGestion')
            ->setFaviconPath('favicon.ico')
            ->renderContentMaximized();
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setDateFormat('dd/MM/Y')
            ->setTimeFormat('HH:mm')
            ->setDateTimeFormat('dd/MM/Y HH:mm')
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(20);
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::section('Gestion'),
            MenuItem::linkToCrud('Véhicules', 'fa fa-car', Vehicule::class),
            MenuItem::linkToCrud('Avaries', 'fa fa-wrench', Avarie::class),
            MenuItem::linkToCrud('Lots', 'fa fa-boxes', Lot::class),
            MenuItem::linkToCrud('Navires', 'fa fa-ship', Navire::class)
        ];
    }
}
