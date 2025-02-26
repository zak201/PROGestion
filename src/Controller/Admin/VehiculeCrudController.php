<?php

namespace App\Controller\Admin;

use App\Entity\Vehicule;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
<<<<<<< HEAD
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\VehiculeRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\VehiculeManager;
=======
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
>>>>>>> a41ffa60622e7aed453f3d4e9d5deadd3dd2711b

class VehiculeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Vehicule::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
<<<<<<< HEAD
            TextField::new('numeroChassis', 'N° Châssis'),
            TextField::new('marque', 'Marque'),
            TextField::new('couleur', 'Couleur'),
            ChoiceField::new('status', 'Statut')
                ->setChoices([
                    'Disponible' => 'disponible',
                    'Bloqué' => 'bloque',
                    'En maintenance' => 'en_maintenance'
                ]),
            AssociationField::new('lot', 'Lot associé'),
            AssociationField::new('navire', 'Navire associé'),
            DateTimeField::new('createdAt', 'Créé le')->onlyOnIndex(),
            DateTimeField::new('updatedAt', 'Modifié le')->onlyOnDetail()
=======
            TextField::new('numeroChassis', 'Numéro de chassis'),
            TextField::new('marque', 'Marque'),
            TextField::new('couleur', 'Couleur'),
            ChoiceField::new('statut', 'Statut')
                ->setChoices([
                    'Disponible' => 'disponible',
                    'En lot' => 'en_lot',
                    'En maintenance' => 'en_maintenance',
                    'Vendu' => 'vendu'
                ]),
            AssociationField::new('lot', 'Lot associé'),
            AssociationField::new('navire', 'Navire')
>>>>>>> a41ffa60622e7aed453f3d4e9d5deadd3dd2711b
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT);
    }

    #[Route('/vehicules', name: 'admin_vehicule_index')]
    public function vehiculeList(VehiculeRepository $vehiculeRepository): Response
    {
        return $this->render('admin/vehicule/index.html.twig', [
            'vehicules' => $vehiculeRepository->findAll()
        ]);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->container->get(VehiculeManager::class)->createVehicule([
            'numeroChassis' => $entityInstance->getNumeroChassis(),
            'marque' => $entityInstance->getMarque(),
            'modele' => $entityInstance->getModele(),
            'couleur' => $entityInstance->getCouleur(),
            'status' => $entityInstance->getStatus(),
            'lot' => $entityInstance->getLot()
        ], false);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->container->get(VehiculeManager::class)->updateStatus(
            $entityInstance, 
            $entityInstance->getStatus()
        );
    }
}
