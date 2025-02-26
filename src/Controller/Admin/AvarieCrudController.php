<?php

namespace App\Controller\Admin;

use App\Entity\Avarie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class AvarieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Avarie::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('titre', 'Titre'),
            TextareaField::new('description', 'Description'),
            AssociationField::new('vehicule', 'Véhicule associé'),
            DateTimeField::new('dateCreation', 'Date de création')->onlyOnIndex(),
            DateTimeField::new('dateSignalement', 'Date signalement'),
        ];
    }
}
