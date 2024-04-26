<?php

namespace App\Controller;

use App\Service\Game;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlayController extends AbstractController
{
    #[Route('/play', name: 'app_play')]
    public function index(): Response
    {
        return $this->render('play/index.html.twig', [
            'controller_name' => 'PlayController',
        ]);
    }

    #[Route('/play/game', name: 'app_play_game')]
    public function playGame(Game $game): Response
    {
        // Get a random image URL from the Game service
        $randomImageUrl = $game->selectRandomImage();

        // Pass the image URL to the Twig template to render it
        return $this->render('play/game.html.twig', [
            'imageUrl' => $randomImageUrl,
        ]);
    }
}
