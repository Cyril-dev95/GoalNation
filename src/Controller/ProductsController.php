<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsController extends AbstractController
{
    // Route pour afficher tous les produits
    #[Route('/produits', name: 'app_products')]
    public function products(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $qb = $this->getFilteredProducts($request, $entityManager);

        $products = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('products/search.html.twig', [
            'products' => $products,
            'query' => $request->query->get('q', ''),
            'sizes' => $request->query->all('sizes'),
            'price' => $request->query->get('price', 0),
            'brands' => $request->query->all('brands'),
            'teams' => $request->query->all('teams'),
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Méthode privée pour obtenir les produits filtrés
    private function getFilteredProducts(Request $request, EntityManagerInterface $entityManager, $categoryKeyword = null)
    {
        $query = $request->query->get('q', '');
        $sizes = $request->query->all('sizes');
        $price = $request->query->get('price', 0);
        $brands = $request->query->all('brands');
        $championships = $request->query->all('teams');
        $clubs = $request->query->all('clubs');

        $qb = $entityManager->getRepository(Products::class)->createQueryBuilder('p');

        if ($categoryKeyword) {
            $qb->andWhere('p.productName LIKE :categoryKeyword OR p.description LIKE :categoryKeyword')
            ->setParameter('categoryKeyword', '%' . $categoryKeyword . '%');
        }

        if ($query) {
            $qb->andWhere('p.productName LIKE :query')
            ->setParameter('query', '%' . $query . '%');
        }

        if (!empty($sizes)) {
            $orX = $qb->expr()->orX();
            foreach ($sizes as $key => $size) {
                $orX->add($qb->expr()->like('p.size', ':size' . $key));
                $qb->setParameter('size' . $key, '%"' . $size . '"%');
            }
            $qb->andWhere($orX);
        }

        if ($price > 0) {
            $qb->andWhere('p.price <= :price')
            ->setParameter('price', $price);
        }

        if (!empty($brands)) {
            $qb->andWhere('p.brand IN (:brands)')
            ->setParameter('brands', $brands);
        }

        if (!empty($championships)) {
            $qb->andWhere('p.championship IN (:championships)')
            ->setParameter('championships', $championships);
        }

        if (!empty($clubs)) {
            $qb->andWhere('p.team IN (:clubs)')
            ->setParameter('clubs', $clubs);
        }

        return $qb;
    }

    // Route pour afficher les maillots homme
    #[Route('/produits/homme/maillots', name: 'app_products_homme_maillots')]
    public function hommeMaillots(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $qb = $this->getFilteredProducts($request, $entityManager, 'Maillot Homme');
        $products = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('products/search.html.twig', [
            'products' => $products,
            'query' => $request->query->get('q', ''),
            'sizes' => $request->query->all('sizes'),
            'price' => $request->query->get('price', 0),
            'brands' => $request->query->all('brands'),
            'teams' => $request->query->all('teams'),
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher les chaussettes homme
    #[Route('/produits/homme/chaussettes', name: 'app_products_homme_chaussettes')]
    public function hommeChaussettes(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $qb = $this->getFilteredProducts($request, $entityManager, 'Chaussettes Homme');
        $products = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('products/search.html.twig', [
            'products' => $products,
            'query' => $request->query->get('q', ''),
            'sizes' => $request->query->all('sizes'),
            'price' => $request->query->get('price', 0),
            'brands' => $request->query->all('brands'),
            'teams' => $request->query->all('teams'),
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher les shorts homme
    #[Route('/produits/homme/shorts', name: 'app_products_homme_shorts')]
    public function shortsHomme(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $qb = $this->getFilteredProducts($request, $entityManager, 'Short Homme');
        $products = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('products/search.html.twig', [
            'products' => $products,
            'query' => $request->query->get('q', ''),
            'sizes' => $request->query->all('sizes'),
            'price' => $request->query->get('price', 0),
            'brands' => $request->query->all('brands'),
            'teams' => $request->query->all('teams'),
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher les produits gardien homme
    #[Route('/produits/homme/gardien', name: 'app_products_homme_gardien')]
    public function gardienHomme(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $qb = $this->getFilteredProducts($request, $entityManager, 'Homme Gardien');
        $products = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('products/search.html.twig', [
            'products' => $products,
            'query' => $request->query->get('q', ''),
            'sizes' => $request->query->all('sizes'),
            'price' => $request->query->get('price', 0),
            'brands' => $request->query->all('brands'),
            'teams' => $request->query->all('teams'),
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher les maillots femme
    #[Route('/produits/femme/maillots', name: 'app_products_femme_maillots')]
    public function femmeMaillots(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $qb = $this->getFilteredProducts($request, $entityManager, 'Maillot Femme');
        $products = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('products/search.html.twig', [
            'products' => $products,
            'query' => $request->query->get('q', ''),
            'sizes' => $request->query->all('sizes'),
            'price' => $request->query->get('price', 0),
            'brands' => $request->query->all('brands'),
            'teams' => $request->query->all('teams'),
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher les shorts femme
    #[Route('/produits/femme/shorts', name: 'app_products_femme_shorts')]
    public function femmeShorts(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $qb = $this->getFilteredProducts($request, $entityManager, 'Short Femme');
        $products = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('products/search.html.twig', [
            'products' => $products,
            'query' => $request->query->get('q', ''),
            'sizes' => $request->query->all('sizes'),
            'price' => $request->query->get('price', 0),
            'brands' => $request->query->all('brands'),
            'teams' => $request->query->all('teams'),
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher les chaussettes femme
    #[Route('/produits/femme/chaussettes', name: 'app_products_femme_chaussettes')]
    public function femmeChaussettes(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $qb = $this->getFilteredProducts($request, $entityManager, 'Chaussettes Femme');
        $products = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('products/search.html.twig', [
            'products' => $products,
            'query' => $request->query->get('q', ''),
            'sizes' => $request->query->all('sizes'),
            'price' => $request->query->get('price', 0),
            'brands' => $request->query->all('brands'),
            'teams' => $request->query->all('teams'),
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher les gardiennes femme
    #[Route('/produits/femme/gardienne', name: 'app_products_femme_gardienne')]
    public function femmeGardiennes(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $qb = $this->getFilteredProducts($request, $entityManager, 'Femme Gardienne');
        $products = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('products/search.html.twig', [
            'products' => $products,
            'query' => $request->query->get('q', ''),
            'sizes' => $request->query->all('sizes'),
            'price' => $request->query->get('price', 0),
            'brands' => $request->query->all('brands'),
            'teams' => $request->query->all('teams'),
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    #[Route('/produits/enfant/maillots', name: 'app_products_enfant_maillots')]
    public function enfantMaillots(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $qb = $this->getFilteredProducts($request, $entityManager, 'Maillot Enfant');
        $products = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('products/search.html.twig', [
            'products' => $products,
            'query' => $request->query->get('q', ''),
            'sizes' => $request->query->all('sizes'),
            'price' => $request->query->get('price', 0),
            'brands' => $request->query->all('brands'),
            'teams' => $request->query->all('teams'),
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher les chaussettes enfant
    #[Route('/produits/enfant/chaussettes', name: 'app_products_enfant_chaussettes')]
    public function enfantChaussettes(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $qb = $this->getFilteredProducts($request, $entityManager, 'Chaussettes Enfant');
        $products = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('products/search.html.twig', [
            'products' => $products,
            'query' => $request->query->get('q', ''),
            'sizes' => $request->query->all('sizes'),
            'price' => $request->query->get('price', 0),
            'brands' => $request->query->all('brands'),
            'teams' => $request->query->all('teams'),
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher les shorts enfant
    #[Route('/produits/enfant/shorts', name: 'app_products_enfant_shorts')]
    public function enfantShorts(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $qb = $this->getFilteredProducts($request, $entityManager, 'Short Enfant');
        $products = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('products/search.html.twig', [
            'products' => $products,
            'query' => $request->query->get('q', ''),
            'sizes' => $request->query->all('sizes'),
            'price' => $request->query->get('price', 0),
            'brands' => $request->query->all('brands'),
            'teams' => $request->query->all('teams'),
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher les gardien enfant
    #[Route('/produits/enfant/gardien', name: 'app_products_enfant_gardien')]
    public function enfantGardien(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $qb = $this->getFilteredProducts($request, $entityManager, 'Gardien Enfant');
        $products = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('products/search.html.twig', [
            'products' => $products,
            'query' => $request->query->get('q', ''),
            'sizes' => $request->query->all('sizes'),
            'price' => $request->query->get('price', 0),
            'brands' => $request->query->all('brands'),
            'teams' => $request->query->all('teams'),
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher un produit spécifique par son ID
    #[Route('/produits/{id<\d+>}', name: 'app_products_show')]
    public function show(int $id, ProductsRepository $productsRepository): Response
    {
        $product = $productsRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }

        // Exemple de détection simple (adapte selon tes données)
        $productName = strtolower($product->getProductName());
        $category = strtolower($product->getCategory());

        $gender = null;
        if (str_contains($productName, 'homme') || str_contains($category, 'homme')) {
            $gender = 'homme';
        } elseif (str_contains($productName, 'femme') || str_contains($category, 'femme')) {
            $gender = 'femme';
        } elseif (str_contains($productName, 'enfant') || str_contains($category, 'enfant')) {
            $gender = 'enfant';
        }

        // Normalise la catégorie pour correspondre à tes routes
        if (str_contains($category, 'maillot')) {
            $cat = 'maillot';
        } elseif (str_contains($category, 'short')) {
            $cat = 'shorts';
        } elseif (str_contains($category, 'chaussette')) {
            $cat = 'chaussettes';
        } elseif (str_contains($category, 'gardien') || str_contains($category, 'gardienne')) {
            $cat = (isset($gender) && $gender === 'femme') ? 'gardienne' : 'gardien';
        } else {
            $cat = $category;
        }

        $categoryKey = $gender ? $gender . '_' . $cat : $cat;

        return $this->render('products/show.html.twig', [
            'product' => $product,
            'categoryKey' => $categoryKey,
        ]);
    }

    // Route pour rechercher des produits
    #[Route('/produits/search', name: 'app_products_search')]
    public function search(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $sizes = $request->query->all('sizes');
        $price = $request->query->get('price', 0);
        $teams = $request->query->all('teams');

        $qb = $entityManager->getRepository(Products::class)->createQueryBuilder('p');

        if (!empty($sizes)) {
            $qb->andWhere('p.size IN (:sizes)')
            ->setParameter('sizes', $sizes);
        }

        if (!empty($teams)) {
            $qb->andWhere('p.team IN (:teams)')
            ->setParameter('teams', $teams);
        }

        if ($price > 0) {
            $qb->andWhere('p.price <= :price')
            ->setParameter('price', $price);
        }

        $products = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('products/search.html.twig', [
            'products' => $products,
            'sizes' => $sizes,
            'price' => $price,
        ]);
    }

    // Route pour obtenir des suggestions de produits
    #[Route('/produits/suggestions', name: 'app_products_suggestions')]
    public function suggestions(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $query = $request->query->get('q', '');
        $products = [];

        if ($query) {
            $products = $entityManager->getRepository(Products::class)->createQueryBuilder('p')
                ->where('p.productName LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult();
        }

        $suggestions = [];

        foreach ($products as $product) {
            $suggestions[] = [
                'id' => $product->getId(),
                'name' => $product->getProductName(),
                'imageUrl' => $product->getImageUrl(),
            ];
        }

        return new JsonResponse($suggestions);
    }

    public function someAction(ProductsRepository $productsRepository)
    {
        $championships = $productsRepository->createQueryBuilder('p')
            ->select('DISTINCT p.championship')
            ->getQuery()
            ->getResult();

        $championships = array_map(fn($row) => $row['championship'], $championships);

        return $this->render('base.html.twig', [
            'championships' => $championships,
        ]);
    }

    #[Route('/produits/championnat/{championship}', name: 'app_products_championship')]
    public function championship(Request $request, string $championship, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $qb = $entityManager->getRepository(Products::class)->createQueryBuilder('p')
            ->where('p.championship = :championship')
            ->setParameter('championship', $championship);

        $products = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('products/search.html.twig', [
            'products' => $products,
            'championship' => $championship,
            'page' => $request->query->getInt('page', 1),
        ]);
    }
}