<?php

namespace App\Controller\Admin;

use App\Entity\Lot;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class LotCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lot::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('reference', 'Référence'),
            TextField::new('description', 'Description'),
            NumberField::new('quantite', 'Quantité'),
            DateTimeField::new('date_creation', 'Date de création')->onlyOnIndex(),
        ];
    }
}
