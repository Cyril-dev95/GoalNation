<?php

namespace App\Controller\Admin;

use App\Entity\Payments;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class PaymentsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Payments::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('orders')->setLabel('Commande'), // Ajouter le champ pour sélectionner la commande
            MoneyField::new('amount')->setCurrency('EUR')
            ->setStoredAsCents(false),
            TextField::new('payment_method'),
            TextField::new('status'),
            TextField::new('currency'),
            DateTimeField::new('payment_date')->hideOnForm(), // Masquer le champ dans le formulaire
            DateTimeField::new('created_at')->hideOnForm(), // Masquer le champ dans le formulaire
        ];
    }
}
