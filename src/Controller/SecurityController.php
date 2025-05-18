<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // Route pour la page de connexion
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Obtenir l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier nom d'utilisateur saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        // Rendre le template 'security/login.html.twig' avec le dernier nom d'utilisateur et l'erreur
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, // Dernier nom d'utilisateur saisi
            'error' => $error, // Erreur de connexion
        ]);
    }

    // Route pour la déconnexion
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode peut être vide - elle sera interceptée par la clé de déconnexion de votre pare-feu
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
