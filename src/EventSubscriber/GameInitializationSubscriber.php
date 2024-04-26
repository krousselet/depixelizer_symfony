<?php

namespace App\EventSubscriber;

use App\Service\Game;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class GameInitializationSubscriber implements EventSubscriberInterface
{
    private $gameService;
    private $router;

    public function __construct(Game $game, RouterInterface $router)
    {
        $this->gameService = $game;
        $this->router = $router;
    }
    public function onKernelRequest(RequestEvent $event): void
    {
        // ...
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function initializeGame(RequestEvent $event)
    {
        $request = $event->getRequest();

        // Check if it's the correct route
        if ($request->attributes->get('_route') === 'start_game') {
            if ($request->isMethod('POST')) {
                // Perform the action to initialize the game
                $this->gameService->initializeNewGame($request->getUser());

                // Redirect to the game page
                $response = new RedirectResponse($this->router->generate('game_page'));
                $event->setResponse($response);
            }
        }
    }
}
