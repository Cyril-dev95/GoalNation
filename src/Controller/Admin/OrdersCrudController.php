<?php

namespace App\Controller\Admin;

use App\Entity\Orders;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrdersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Orders::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
        AssociationField::new('user'),
        TextField::new('status'),
        MoneyField::new('total')->setCurrency('EUR')->setStoredAsCents(false),
        TextField::new('payment_method'),
        TextField::new('shipping_status'),
        DateTimeField::new('orderDate')->hideOnForm(),
        // Pour afficher les détails de la commande (OrderDetails), tu peux utiliser :
        AssociationField::new('orderDetails')->onlyOnDetail(),
    ];
    }
    
}
