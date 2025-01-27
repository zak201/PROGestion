<?php

namespace App\Controller\Admin;

use App\Entity\Camion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class CamionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Camion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('immatriculation'),
            TextField::new('conducteur'),
            ChoiceField::new('statut')->setChoices([
                'Disponible' => 'disponible',
                'En mission' => 'en_mission',
                'En maintenance' => 'maintenance'
            ]),
            AssociationField::new('lots')
        ];
    }
} 