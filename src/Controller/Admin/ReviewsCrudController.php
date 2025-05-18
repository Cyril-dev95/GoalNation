<?php

namespace App\Controller\Admin;

use App\Entity\Reviews;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ReviewsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reviews::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user', 'Utilisateur'),
            AssociationField::new('product', 'Produit'),
            TextField::new('rating', 'Note'),
            DateTimeField::new('created_at', 'Date de création')->hideOnForm(), // Masquer le champ dans le formulaire
        ];
    }
}
