<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user/personal-info', name: 'user_personal_info')]
    public function personalInfo(): Response
    {
        return $this->render('user/personnal_info.html.twig');
    }

    #[Route('/user/orders', name: 'user_orders')]
    public function orders(): Response
    {
        $user = $this->getUser();
        $orders = ($user instanceof \App\Entity\User) ? $user->getOrders() : [];

        return $this->render('user/order_users.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/user/address', name: 'user_address')]
    public function address(): Response
    {
        return $this->render('user/address.html.twig');
    }

    #[Route('/user/edit_address', name: 'user_edit_address')]
    public function editAddress(\Symfony\Component\HttpFoundation\Request $request, \Doctrine\ORM\EntityManagerInterface $em): Response
    {
        // Ce commentaire PHPDoc indique à l'IDE que la variable $user est soit une instance de App\Entity\User, soit null.
        // Cela permet d'avoir l'autocomplétion et d'éviter les fausses alertes sur les méthodes de l'entité User.
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();

        if ($request->isMethod('POST')) {
            $newAddress = $request->request->get('address');
            if ($user && $newAddress) {
                $user->setAddress($newAddress);
                $em->flush();
                $this->addFlash('success', 'Adresse mise à jour avec succès.');
                return $this->redirectToRoute('user_address');
            }
        }

        return $this->render('user/edit_address.html.twig');
    }
}
