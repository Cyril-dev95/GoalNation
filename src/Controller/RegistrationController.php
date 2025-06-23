<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    // Route pour la page d'inscription
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Créer un nouvel utilisateur
        $user = new User();
        
        // Créer le formulaire d'inscription et l'associer à l'utilisateur
        $form = $this->createForm(RegistrationType::class, $user);
        
        // Gérer la requête du formulaire
        $form->handleRequest($request);

        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Encoder le mot de passe
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            // Définir le champ created_at
            $user->setCreatedAt(new DateTimeImmutable());

            // Enregistrer l'utilisateur
            $entityManager->persist($user);
            $entityManager->flush();

            // Ajoute le message flash de succès
            $this->addFlash('success', 'Votre compte a bien été créé, vous pouvez vous connecter !');

            // Rediriger vers la page de connexion
            return $this->redirectToRoute('app_login');
        } elseif ($form->isSubmitted()) {
            // Ajoute un message flash d'erreur général si le formulaire est soumis mais non valide
            $this->addFlash('error', 'Veuillez corriger les erreurs dans le formulaire.');
        }

        // Rendre le template 'registration/register.html.twig' avec le formulaire d'inscription
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
