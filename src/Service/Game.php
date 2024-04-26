<?php

namespace App\Service;

class Game
{
    public function __construct()
    {
        // Assuming the root of your Symfony project is the current working directory
        $this->imagesPath = $_SERVER['DOCUMENT_ROOT'] . '/images/';
    }
    public function selectRandomImage(): string
    {
        // Check if the directory exists
        if (!is_dir($this->imagesPath)) {
            throw new \Exception("Le dossier 'images' n'existe pas.");
        }

        // Read files from the directory, ignoring '.' and '..'
        $files = array_diff(scandir($this->imagesPath), array('..', '.'));
        $files = array_filter($files, function ($file) {
            return !is_dir($this->imagesPath . $file); // Ensure that only files are considered
        });

        if (empty($files)) {
            throw new \Exception("Aucune image dans le dossier.");
        }

        // Select a random file
        $randomFile = $files[array_rand($files)];

        // Return the web-accessible path to the file
        return '/images/' . $randomFile;
    }
    public function initializeNewGame($user)
    {
        // Select a random image from your database
        $image = $this->selectRandomImage();

        // Initialize game settings
        // Save these details in the database or session
    }
}