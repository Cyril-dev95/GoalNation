<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Orders;
use App\Entity\Reviews;
use App\Entity\Payments;
use App\Entity\Products;
use App\Entity\OrderDetails;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Sécurisation : seul l'admin avec cet email peut accéder au dashboard
        $user = $this->getUser();
        if (!$user || $user->getUserIdentifier() !== 'CYRA') {
            throw $this->createAccessDeniedException('Accès réservé à l\'administrateur.');
        }

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('GoalNation');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Orders', 'fas fa-shopping-cart', Orders::class);
        yield MenuItem::linkToCrud('Order Details', 'fas fa-shopping-cart', OrderDetails::class);
        yield MenuItem::linkToCrud('Payments', 'fas fa-credit-card', Payments::class);
        yield MenuItem::linkToCrud('Products', 'fas fa-shopping-bag', Products::class);
        yield MenuItem::linkToCrud('Reviews', 'fas fa-star', Reviews::class);
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
    }
}
