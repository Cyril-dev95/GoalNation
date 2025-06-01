<?php
namespace App\Controller;

use App\Entity\Products;
use App\Entity\OrderDetails;
use App\Service\NewsApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(EntityManagerInterface $em, NewsApiService $newsApiService): Response
    {
        $latestProducts = $em->getRepository(Products::class)
            ->createQueryBuilder('p')
            ->orderBy('p.created_at', 'DESC')
            ->setMaxResults(9)
            ->getQuery()
            ->getResult();

        // Récupérer les 9 produits les plus vendus
        $topSales = $em->createQueryBuilder()
            ->select('p, SUM(od.quantity) AS salesCount')
            ->from(Products::class, 'p')
            ->leftJoin(OrderDetails::class, 'od', 'WITH', 'od.product = p.id')
            ->groupBy('p.id')
            ->orderBy('salesCount', 'DESC')
            ->setMaxResults(9)
            ->getQuery()
            ->getResult();

         $news = $newsApiService->getFootballNews();
        $articles = $news['articles'] ?? [];

        // Tri du plus récent au plus ancien
        usort($articles, function ($a, $b) {
            return strtotime($b['publishedAt']) <=> strtotime($a['publishedAt']);
        });

        // Prendre les 9 premiers
        $latestNews = array_slice($articles, 0, 9);
             
        return $this->render('home.html.twig', [
            'latestProducts' => $latestProducts,
            'topSales' => array_column($topSales, 0), // On ne garde que les entités produit
            'latestNews' => $latestNews,
        ]);
        
    }
}