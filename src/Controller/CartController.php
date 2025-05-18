<?php
namespace App\Controller;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CartController extends AbstractController
{
    // Route pour afficher le panier
    #[Route('/cart', name: 'app_cart')]
    #[IsGranted('ROLE_USER')] // Vérifie que l'utilisateur a le rôle 'ROLE_USER'
    public function index(SessionInterface $session): Response
    {
        // Récupérer le panier depuis la session, ou un tableau vide s'il n'existe pas
        $cart = $session->get('cart', []);
        $total = 0; // Initialiser le total à 0

        // Calculer le total du panier
        foreach ($cart as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }

        // Rendre le template 'cart/index.html.twig' avec le panier et le total
        return $this->render('cart/index.html.twig', [
            'cart' => $cart, // Contenu du panier
            'total' => $total, // Total du panier
        ]);
    }

    // Route pour ajouter un produit au panier
    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    #[IsGranted('ROLE_USER')] // Vérifie que l'utilisateur a le rôle 'ROLE_USER'
    public function add(int $id, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le produit depuis la base de données par son ID
        $product = $entityManager->getRepository(Products::class)->find($id);

        // Si le produit n'existe pas, lancer une exception
        if (!$product) {
            throw $this->createNotFoundException('Le produit n\'existe pas');
        }

        // Récupérer le panier depuis la session, ou un tableau vide s'il n'existe pas
        $cart = $session->get('cart', []);

        // Si le produit est déjà dans le panier, augmenter la quantité
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // Sinon, ajouter le produit au panier avec une quantité de 1
            $cart[$id] = [
                'product' => $product,
                'quantity' => 1,
            ];
        }

        // Mettre à jour le panier dans la session
        $session->set('cart', $cart);

        // Rediriger vers la page du panier
        return $this->redirectToRoute('app_cart');
    }

    // Route pour supprimer un produit du panier
    #[Route('/cart/remove/{id}', name: 'app_cart_remove', methods: ['POST'])]
    #[IsGranted('ROLE_USER')] // Vérifie que l'utilisateur a le rôle 'ROLE_USER'
    public function remove(int $id, SessionInterface $session): Response
    {
        // Récupérer le panier depuis la session, ou un tableau vide s'il n'existe pas
        $cart = $session->get('cart', []);

        // Si le produit est dans le panier, le supprimer
        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        // Mettre à jour le panier dans la session
        $session->set('cart', $cart);

        // Rediriger vers la page du panier
        return $this->redirectToRoute('app_cart');
    }

    // Route pour augmenter la quantité d'un produit dans le panier
    #[Route('/cart/increase/{id}', name: 'app_cart_increase', methods: ['POST'])]
    public function increase(int $id, SessionInterface $session): Response
    {
        // Récupérer le panier depuis la session, ou un tableau vide s'il n'existe pas
        $cart = $session->get('cart', []);

        // Si le produit est dans le panier, augmenter la quantité
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        }

        // Mettre à jour le panier dans la session
        $session->set('cart', $cart);

        // Rediriger vers la page du panier
        return $this->redirectToRoute('app_cart');
    }

    // Route pour diminuer la quantité d'un produit dans le panier
    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease', methods: ['POST'])]
    public function decrease(int $id, SessionInterface $session): Response
    {
        // Récupérer le panier depuis la session, ou un tableau vide s'il n'existe pas
        $cart = $session->get('cart', []);

        // Si le produit est dans le panier
        if (isset($cart[$id])) {
            // Si la quantité est supérieure à 1, diminuer la quantité
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            } else {
                // Sinon, supprimer le produit du panier
                unset($cart[$id]);
            }
        }

        // Mettre à jour le panier dans la session
        $session->set('cart', $cart);

        // Rediriger vers la page du panier
        return $this->redirectToRoute('app_cart');
    }
}
?>