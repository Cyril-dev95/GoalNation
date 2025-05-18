<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Ajouter un champ 'username' de type TextType avec un label personnalisé
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur'
            ])
            // Ajouter un champ 'password' de type PasswordType avec un label personnalisé
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe'
            ])
            // Ajouter un champ 'first_name' de type TextType avec un label personnalisé
            ->add('first_name', TextType::class, [
                'label' => 'Prénom'
            ])
            // Ajouter un champ 'last_name' de type TextType avec un label personnalisé
            ->add('last_name', TextType::class, [
                'label' => 'Nom'
            ])
            // Ajouter un champ 'email' de type EmailType avec un label personnalisé
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            // Ajouter un champ 'phone_number' de type TextType avec un label personnalisé
            ->add('phone_number', TextType::class, [
                'label' => 'Numéro de téléphone'
            ])
            // Ajouter un champ 'address' de type TextType avec un label personnalisé
            ->add('address', TextType::class, [
                'label' => 'Adresse'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Définir les options par défaut pour ce formulaire
        $resolver->setDefaults([
            'data_class' => User::class, // Spécifie que ce formulaire est associé à l'entité User
        ]);
    }
}
