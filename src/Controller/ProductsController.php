<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsController extends AbstractController
{
    // Route pour afficher tous les produits
    #[Route('/products', name: 'app_products')]
    public function products(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Obtenir les produits filtrés
        $products = $this->getFilteredProducts($request, $entityManager);

        // Rendre le template 'products/index.html.twig' avec les produits et les paramètres de recherche
        return $this->render('products/search.html.twig', [
            'products' => $products, // Liste des produits
            'query' => $request->query->get('q', ''), // Requête de recherche
            'sizes' => $request->query->all('sizes'), // Tailles sélectionnées
            'price' => $request->query->get('price', 0), // Prix maximum
            'brands' => $request->query->all('brands'), // Marques sélectionnées
            'teams' => $request->query->all('teams'), // Équipes sélectionnées
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Méthode privée pour obtenir les produits filtrés
    private function getFilteredProducts(Request $request, EntityManagerInterface $entityManager, $categoryKeyword = null, $limit = 30): array
    {
        $query = $request->query->get('q', ''); // Requête de recherche
        $sizes = $request->query->all('sizes'); // Tailles sélectionnées
        $price = $request->query->get('price', 0); // Prix maximum
        $brands = $request->query->all('brands'); // Marques sélectionnées
        $championships = $request->query->all('teams'); // Équipes sélectionnées
        $clubs = $request->query->all('clubs');

        // Créer un QueryBuilder pour la requête
        $qb = $entityManager->getRepository(Products::class)->createQueryBuilder('p');
        $qb->setMaxResults($limit); // Limite le nombre de résultats

        // Ajouter une condition pour filtrer par mot-clé dans le nom ou la description
        if ($categoryKeyword) {
            $qb->andWhere('p.productName LIKE :categoryKeyword OR p.description LIKE :categoryKeyword')
            ->setParameter('categoryKeyword', '%' . $categoryKeyword . '%');
        }

        if ($query) {
            $qb->andWhere('p.productName LIKE :query')
            ->setParameter('query', '%' . $query . '%');
        }

        if (!empty($sizes)) {
        $orX = $qb->expr()->orX(); // Crée une expression OR
        foreach ($sizes as $key => $size) {
            $orX->add($qb->expr()->like('p.size', ':size' . $key));
            $qb->setParameter('size' . $key, '%"' . $size . '"%'); // Recherche la taille dans la liste sérialisée
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

        return $qb->getQuery()->getResult();
    }

    // Route pour afficher les maillots homme
    #[Route('/products/homme/maillots', name: 'app_products_homme_maillots')]
    public function hommeMaillots(Request $request, EntityManagerInterface $entityManager): Response
    {

        $products = $this->getFilteredProducts($request, $entityManager, 'Maillot Homme');

        $response = $this->render('products/search.html.twig', [
            'products' => $products,
            'query' => $request->query->get('q', ''),
            'sizes' => $request->query->all('sizes'),
            'price' => $request->query->get('price', 0),
            'brands' => $request->query->all('brands'),
            'teams' => $request->query->all('teams'),
            'clubs' => $request->query->all('clubs'),
        ]);

        return $response;
    }

    // Route pour afficher les chaussettes homme
    #[Route('/products/homme/chaussettes', name: 'app_products_homme_chaussettes')]
    public function hommeChaussettes(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Obtenir les produits filtrés pour les chaussettes homme
        $products = $this->getFilteredProducts($request, $entityManager, 'Chaussettes Homme');

        return $this->render('products/search.html.twig', [
            'products' => $products, // Liste des produits
            'query' => $request->query->get('q', ''), // Requête de recherche
            'sizes' => $request->query->all('sizes'), // Tailles sélectionnées
            'price' => $request->query->get('price', 0), // Prix maximum
            'brands' => $request->query->all('brands'), // Marques sélectionnées
            'teams' => $request->query->all('teams'), // Équipes sélectionnées
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher les shorts homme
    #[Route('/products/homme/shorts', name: 'app_products_homme_shorts')]
    public function shortsHomme(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Obtenir les produits filtrés pour les shorts homme
        $products = $this->getFilteredProducts($request, $entityManager, 'Short Homme');

        return $this->render('products/search.html.twig', [
            'products' => $products, // Liste des produits
            'query' => $request->query->get('q', ''), // Requête de recherche
            'sizes' => $request->query->all('sizes'), // Tailles sélectionnées
            'price' => $request->query->get('price', 0), // Prix maximum
            'brands' => $request->query->all('brands'), // Marques sélectionnées
            'teams' => $request->query->all('teams'), // Équipes sélectionnées
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher les produits gardien homme
    #[Route('/products/homme/gardien', name: 'app_products_homme_gardien')]
    public function gardienHomme(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Obtenir les produits filtrés pour les gardien homme
        $products = $this->getFilteredProducts($request, $entityManager, 'Homme Gardien');

        return $this->render('products/search.html.twig', [
            'products' => $products, // Liste des produits
            'query' => $request->query->get('q', ''), // Requête de recherche
            'sizes' => $request->query->all('sizes'), // Tailles sélectionnées
            'price' => $request->query->get('price', 0), // Prix maximum
            'brands' => $request->query->all('brands'), // Marques sélectionnées
            'teams' => $request->query->all('teams'), // Équipes sélectionnées
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher les maillots femme
    #[Route('/products/femme/maillots', name: 'app_products_femme_maillots')]
    public function femmeMaillots(Request $request, EntityManagerInterface $entityManager): Response
    {
        $products = $this->getFilteredProducts($request, $entityManager, 'Maillot Femme');

        return $this->render('products/search.html.twig', [
            'products' => $products, // Liste des produits
            'query' => $request->query->get('q', ''), // Requête de recherche
            'sizes' => $request->query->all('sizes'), // Tailles sélectionnées
            'price' => $request->query->get('price', 0), // Prix maximum
            'brands' => $request->query->all('brands'), // Marques sélectionnées
            'teams' => $request->query->all('teams'), // Équipes sélectionnées
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher les shorts femme
    #[Route('/products/femme/shorts', name: 'app_products_femme_shorts')]
    public function femmeShorts(Request $request, EntityManagerInterface $entityManager): Response
    {
        $products = $this->getFilteredProducts($request, $entityManager, 'Short Femme');

        return $this->render('products/search.html.twig', [
            'products' => $products, // Liste des produits
            'query' => $request->query->get('q', ''), // Requête de recherche
            'sizes' => $request->query->all('sizes'), // Tailles sélectionnées
            'price' => $request->query->get('price', 0), // Prix maximum
            'brands' => $request->query->all('brands'), // Marques sélectionnées
            'teams' => $request->query->all('teams'), // Équipes sélectionnées
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher les chaussettes femme
    #[Route('/products/femme/chaussettes', name: 'app_products_femme_chaussettes')]
    public function femmeChaussettes(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Obtenir les produits filtrés pour les chaussettes femme
        $products = $this->getFilteredProducts($request, $entityManager, 'Chaussettes Femme');

        return $this->render('products/search.html.twig', [
            'products' => $products, // Liste des produits
            'query' => $request->query->get('q', ''), // Requête de recherche
            'sizes' => $request->query->all('sizes'), // Tailles sélectionnées
            'price' => $request->query->get('price', 0), // Prix maximum
            'brands' => $request->query->all('brands'), // Marques sélectionnées
            'teams' => $request->query->all('teams'), // Équipes sélectionnées
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher les gardiennes femme
    #[Route('/products/femme/gardienne', name: 'app_products_femme_gardienne')]
    public function femmeGardiennes(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Obtenir les produits filtrés pour les gardiennes femme
        $products = $this->getFilteredProducts($request, $entityManager, 'Femme Gardienne');

        return $this->render('products/search.html.twig', [
            'products' => $products, // Liste des produits
            'query' => $request->query->get('q', ''), // Requête de recherche
            'sizes' => $request->query->all('sizes'), // Tailles sélectionnées
            'price' => $request->query->get('price', 0), // Prix maximum
            'brands' => $request->query->all('brands'), // Marques sélectionnées
            'teams' => $request->query->all('teams'), // Équipes sélectionnées
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    #[Route('/products/enfant/maillots', name: 'app_products_enfant_maillots')]
    public function enfantMaillots(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les produits pour la catégorie "Enfant" et le type "Maillots"
        $products = $this->getFilteredProducts($request, $entityManager, 'Maillot Enfant');

        return $this->render('products/search.html.twig', [
            'products' => $products, // Liste des produits
            'query' => $request->query->get('q', ''), // Requête de recherche
            'sizes' => $request->query->all('sizes'), // Tailles sélectionnées
            'price' => $request->query->get('price', 0), // Prix maximum
            'brands' => $request->query->all('brands'), // Marques sélectionnées
            'teams' => $request->query->all('teams'), // Équipes sélectionnées
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher les chaussettes enfant
    #[Route('/products/enfant/chaussettes', name: 'app_products_enfant_chaussettes')]
    public function enfantChaussettes(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Obtenir les produits filtrés pour les chaussettes enfant
        $products = $this->getFilteredProducts($request, $entityManager, 'Chaussettes Enfant');

        return $this->render('products/search.html.twig', [
            'products' => $products, // Liste des produits
            'query' => $request->query->get('q', ''), // Requête de recherche
            'sizes' => $request->query->all('sizes'), // Tailles sélectionnées
            'price' => $request->query->get('price', 0), // Prix maximum
            'brands' => $request->query->all('brands'), // Marques sélectionnées
            'teams' => $request->query->all('teams'), // Équipes sélectionnées
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher les shorts enfant
    #[Route('/products/enfant/shorts', name: 'app_products_enfant_shorts')]
    public function enfantShorts(Request $request, EntityManagerInterface $entityManager): Response
    {
        $products = $this->getFilteredProducts($request, $entityManager, 'Short Enfant');

        return $this->render('products/search.html.twig', [
            'products' => $products, // Liste des produits
            'query' => $request->query->get('q', ''), // Requête de recherche
            'sizes' => $request->query->all('sizes'), // Tailles sélectionnées
            'price' => $request->query->get('price', 0), // Prix maximum
            'brands' => $request->query->all('brands'), // Marques sélectionnées
            'teams' => $request->query->all('teams'), // Équipes sélectionnées
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher les gardien enfant
    #[Route('/products/enfant/gardien', name: 'app_products_enfant_gardien')]
    public function enfantGardien(Request $request, EntityManagerInterface $entityManager): Response
    {
        $products = $this->getFilteredProducts($request, $entityManager, 'Gardien Enfant');

        return $this->render('products/search.html.twig', [
            'products' => $products, // Liste des produits
            'query' => $request->query->get('q', ''), // Requête de recherche
            'sizes' => $request->query->all('sizes'), // Tailles sélectionnées
            'price' => $request->query->get('price', 0), // Prix maximum
            'brands' => $request->query->all('brands'), // Marques sélectionnées
            'teams' => $request->query->all('teams'), // Équipes sélectionnées
            'clubs' => $request->query->all('clubs'),
        ]);
    }

    // Route pour afficher un produit spécifique par son ID
    #[Route('/products/{id<\d+>}', name: 'app_products_show')]
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
    #[Route('/products/search', name: 'app_products_search')]
    public function search(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sizes = $request->query->all('sizes'); // Récupérer les tailles sélectionnées
        $price = $request->query->get('price', 0); // Récupérer le prix maximum

        // Créer un QueryBuilder pour filtrer les produits
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

        $qb->setMaxResults(30); // Limite à 30 produits

        $products = $qb->getQuery()->getResult();

        return $this->render('products/search.html.twig', [
            'products' => $products,
            'sizes' => $sizes,
            'price' => $price,
        ]);
    }

    // Route pour obtenir des suggestions de produits
    #[Route('/products/suggestions', name: 'app_products_suggestions')]
    public function suggestions(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Obtenir la requête de recherche depuis les paramètres de la requête
        $query = $request->query->get('q', '');
        $products = []; // Initialiser un tableau vide pour les produits

        // Si la requête de recherche n'est pas vide, récupérer les produits correspondants
        if ($query) {
            $products = $entityManager->getRepository(Products::class)->createQueryBuilder('p')
                ->where('p.productName LIKE :query') // Filtrer les produits par nom
                ->setParameter('query', '%' . $query . '%') // Définir le paramètre de la requête
                ->setMaxResults(10) // Limiter le nombre de résultats à 10
                ->getQuery()
                ->getResult(); // Exécuter la requête et obtenir les résultats
        }

        $suggestions = []; // Initialiser un tableau vide pour les suggestions

        // Itérer sur les produits obtenus pour créer les suggestions
        foreach ($products as $product) {
            $suggestions[] = [
                'id' => $product->getId(), // ID du produit
                'name' => $product->getProductName(), // Nom du produit
                'imageUrl' => $product->getImageUrl(), // URL de l'image du produit
            ];
        }

        // Retourner les suggestions sous forme de réponse JSON
        return new JsonResponse($suggestions);
    }

    public function someAction(ProductsRepository $productsRepository)
    {
        $championships = $productsRepository->createQueryBuilder('p')
            ->select('DISTINCT p.championship')
            ->getQuery()
            ->getResult();

        // Transforme le résultat en tableau simple
        $championships = array_map(fn($row) => $row['championship'], $championships);

        return $this->render('base.html.twig', [
            'championships' => $championships,
        ]);
    }

    #[Route('/products/championship/{championship}', name: 'app_products_championship')]
    public function championship(Request $request, string $championship, EntityManagerInterface $entityManager): Response
    {
        $limit = 30; // Nombre de produits par page
        $page = max(1, (int)$request->query->get('page', 1));
        $offset = ($page - 1) * $limit;

        $qb = $entityManager->getRepository(Products::class)->createQueryBuilder('p')
            ->where('p.championship = :championship')
            ->setParameter('championship', $championship)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $products = $qb->getQuery()->getResult();

        return $this->render('products/search.html.twig', [
            'products' => $products,
            'championship' => $championship,
            'page' => $page,
        ]);
    }
}