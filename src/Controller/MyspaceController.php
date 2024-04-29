<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MyspaceController extends AbstractController
{
    #[Route('/profile/myspace', name: 'app_myspace')]
    public function index(
    ): Response
    {
        $user = $this->getUser();
        return $this->render('myspace/index.html.twig', [
            'user' => $user
        ]);
    }
}
