<?php

namespace App\Controller\Admin;

use App\Entity\Vehicule;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class VehiculeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Vehicule::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
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
        ];
    }
}
