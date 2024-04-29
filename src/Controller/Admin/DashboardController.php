<?php

namespace App\Controller\Admin;

use App\Entity\GameImage;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly AdminUrlGenerator $adminUrlGenerator,
    )
    {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(DashboardController::class)
            ->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Depixelizer');
    }

    //THIS SECTION IS USED TO CUSTOMIZE THE LEFT BAR IN THE ADMINISTRATION PANNEL
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::section('Images du jeu');
        yield MenuItem::section('Règles du jeu');
        yield MenuItem::subMenu('Actions utilisateurs', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Voir mes Utilisateurs', 'fas fa-eye', User::class),
            MenuItem::linkToCrud('Ajouter un utilisateur', 'fas fa-plus', User::class)->setAction(crud::PAGE_NEW)
        ]);
        yield MenuItem::subMenu('Actions jeu', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Voir mes images de jeu', 'fas fa-eye', GameImage::class),
            MenuItem::linkToCrud('Ajouter une image de jeu', 'fas fa-plus', GameImage::class)->setAction(crud::PAGE_NEW)
        ]);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
