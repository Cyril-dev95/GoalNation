<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Products::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('product_name'),
            TextareaField::new('description'),
            MoneyField::new('price')->setCurrency('EUR')
            ->setStoredAsCents(false),
            IntegerField::new('stock_quantity'),
            ChoiceField::new('category', 'Catégorie')
                ->setChoices([
                    'Maillots' => 'Maillots',
                    'Shorts' => 'Shorts',
                    'Chaussettes' => 'Chaussettes',
                    'Crampons' => 'Crampons',
                ]),
            ChoiceField::new('brand', 'Marque')
                ->setChoices([
                    'Nike' => 'Nike',
                    'Adidas' => 'Adidas',
                    'Puma' => 'Puma',
                    'New Balance' => 'New Balance',
                    'Kappa' => 'Kappa',
                    'Macron' => 'Macron',
                    'Le Coq Sportif' => 'Le Coq Sportif',
                    'Umbro' => 'Umbro',
                    'Hummel' => 'Hummel',
                    'Joma' => 'Joma',
                    'Errea' => 'Errea',
                    'Uhlsport' => 'Uhlsport',
                    'Kelme' => 'Kelme',
                    'Castore' => 'Castore',
                    'Lotto' => 'Lotto',
                    'Jako' => 'Jako',
                ]),            
            TextField::new('image_url', 'URL de l\'image'),
            TextareaField::new('additionnal_images'),
            TextField::new('team'),
            ChoiceField::new('championship', 'Championnat')
                ->setChoices([
                    'Ligue 1' => 'Ligue 1',
                    'Premier League' => 'Premier League',
                    'La Liga' => 'La Liga',
                    'Bundesliga' => 'Bundesliga',
                    'Serie A' => 'Serie A',
                    'Liga Portugal' => 'Liga Portugal',
                    'MLS' => 'MLS',
                ]),
            ChoiceField::new('size', 'Tailles disponibles')
                ->setChoices([
                    'S' => 'S',
                    'M' => 'M',
                    'L' => 'L',
                    'XL' => 'XL',
                    'XXL' => 'XXL',
                    '4-6ans' => '4-6ans',
                    '6-8ans' => '6-8ans',
                    '8-10ans' => '8-10ans',
                    '10-12ans' => '10-12ans',
                    '12-14ans' => '12-14ans',
                    '14-16ans' => '14-16ans',
                    '35-38' => '35-38',
                    '39-42' => '39-42',
                    '43-46' => '43-46',
                    '36' => '36',
                    '37' => '37',
                    '38' => '38',
                    '39' => '39',
                    '40' => '40',
                    '41' => '41',
                    '42' => '42',
                    '43' => '43',
                    '44' => '44',
                    '45' => '45',
                    '46' => '46',
                ])
                ->allowMultipleChoices(true),
            TextField::new('rating')->onlyOnForms(),
            IntegerField::new('reviews_count')->onlyOnForms(),
            DateTimeField::new('created_at')->hideOnForm()->hideOnIndex(), // Masquer le champ dans le formulaire
        ];
    }
}
