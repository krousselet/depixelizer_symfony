<?php

namespace App\Service;

use App\Entity\GameImage;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Game
{
    private EntityManagerInterface $entityManager;
    private string $imagesDirectory;

    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $params)
    {
        $this->entityManager = $entityManager;
        // Assuming you have set a parameter for images directory
        $this->imagesDirectory = '/public/images';
    }

    public function selectRandomImage(): string
    {
        $imageRepository = $this->entityManager->getRepository(GameImage::class);
        $images = $imageRepository->findAll();

        if (empty($images)) {
            throw new Exception("Aucune image disponible dans la base de données.");
        }

        // Select a random GameImage entity
        $randomImage = $images[array_rand($images)];

        return $this->imagesDirectory . $randomImage->getFilePath();
    }

    public function initializeNewGame($user): void
    {
        // Select a random image path
        try {
            $imagePath = $this->selectRandomImage();
        } catch (Exception $e) {
        }

        // Initialize game settings
        // This would involve setting up a new game session, possibly storing state in the database
        // Save these details in the database or session
    }
}
