<?php

namespace App\Controller\Admin;

use App\Entity\Vehicule;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class VehiculeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Vehicule::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('marque', 'Marque'),
            TextField::new('modele', 'Modèle'),
            NumberField::new('annee', 'Année'),
            TextField::new('immatriculation', 'Immatriculation'),
            AssociationField::new('lot', 'Lot associé'),
        ];
    }
}
