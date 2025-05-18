<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('username'),
            TextField::new('password')->setFormType(PasswordType::class), // Ajouter le champ de mot de passe
            TextField::new('first_name'),
            TextField::new('last_name'),
            EmailField::new('email'),
            TextField::new('phone_number'),
            TextareaField::new('address'),
            DateTimeField::new('created_at')->hideOnForm(), // Masquer le champ dans le formulaire
        ];
    }    
}
