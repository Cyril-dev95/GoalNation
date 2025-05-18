<?php

namespace App\Controller;

use App\Entity\Reviews;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ReviewsController extends AbstractController
{
    #[Route('/reviews', name: 'app_reviews')]
    public function index(): Response
    {
        return $this->render('reviews/index.html.twig', [
            'controller_name' => 'ReviewsController',
        ]);
    }

    #[Route('/reviews/add/{productId}', name: 'app_reviews_add', methods: ['POST'])]
    public function add(Request $request, ProductsRepository $productsRepository, EntityManagerInterface $em, int $productId): Response
    {
        $product = $productsRepository->find($productId);
        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }

        $rating = (int) $request->request->get('rating');
        $rating = max(0, min(10, $rating));

        $review = new Reviews();
        $review->setProduct($product);
        $review->setRating($rating);

        // Associer l'utilisateur connecté
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour laisser un avis.');
        }
        $review->setUser($user);

        $em->persist($review);
        $em->flush();

        return $this->redirectToRoute('app_products_show', ['id' => $productId]);
    }
}
