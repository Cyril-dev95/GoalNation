<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // Route pour la page de connexion
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($request->query->get('success')) {
            $this->addFlash('success', 'Connexion réussie !');
        }

        // Ajoute un flash error si erreur de connexion
        if ($error) {
            $this->addFlash('error', 'Identifiants invalides.');
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => null, // On ne transmet plus l'erreur à Twig
        ]);
    }

    // Route pour la déconnexion
    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode peut être vide - elle sera interceptée par la clé de déconnexion de votre pare-feu
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
