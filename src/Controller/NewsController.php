<?php

namespace App\Controller;

use App\Service\NewsApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    #[Route('/news', name: 'app_news')]
    public function index(NewsApiService $newsApiService): Response
    {
        $news = $newsApiService->getFootballNews();
        $articles = $news['articles'] ?? [];

        // Tri du plus récent au plus ancien
        usort($articles, function ($a, $b) {
            return strtotime($b['publishedAt']) <=> strtotime($a['publishedAt']);
        });

        return $this->render('news/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}