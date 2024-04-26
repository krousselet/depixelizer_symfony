<?php

namespace App\Service;

use Imagine\Gd\Imagine;

class ImageProcessorService
{
    private $imagine;

    public function __construct()
    {
        $this->imagine = new Imagine();
    }

    public function getBlurredImage(string $imagePath, int $level): string
    {
        // Load the image
        $image = $this->imagine->open($imagePath);

        // Define the blur levels as an array, more levels can be added as needed
        $blurLevels = [
            0 => 20,  // Heavily blurred
            1 => 15,
            2 => 10,
            3 => 5,
            4 => 0   // No blur (clear image)
        ];

        // Check if the provided level exists in the array
        if (array_key_exists($level, $blurLevels)) {
            $blurAmount = $blurLevels[$level];
        } else {
            throw new \InvalidArgumentException("Invalid blur level provided.");
        }

        // Apply the blur filter
        if ($blurAmount > 0) {
            $image->effects()->blur($blurAmount);
        }

        // Save or output the image to a temporary location or directly output as string (base64)
        $tempPath = sys_get_temp_dir() . '/blurred_image.png';
        $image->save($tempPath);

        return $tempPath;
    }

}