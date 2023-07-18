<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Media;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Controller\Admin\ProductCrudController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ProductCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('EBoutiqueSymphony');
    }

    public function configureMenuItems(): iterable
    {
        return
        [
           yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),
           yield MenuItem::linkToCrud('Products', 'fas fa-list', Product::class),
           yield MenuItem::linkToCrud('Categories', 'fas fa-tag', Category::class),
           yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class),
           yield MenuItem::linkToCrud('Media', 'fa fa-file', Media::class),

        ];
    }
}
