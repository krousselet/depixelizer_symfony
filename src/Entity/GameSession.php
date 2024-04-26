<?php

namespace App\Entity;

use App\Repository\GameSessionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameSessionRepository::class)]
class GameSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column]
    private ?int $currentGuessCount = null;

    #[ORM\Column]
    private ?int $currentImageLevel = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column]
    private ?int $gameScore = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getCurrentGuessCount(): ?int
    {
        return $this->currentGuessCount;
    }

    public function setCurrentGuessCount(int $currentGuessCount): static
    {
        $this->currentGuessCount = $currentGuessCount;

        return $this;
    }

    public function getCurrentImageLevel(): ?int
    {
        return $this->currentImageLevel;
    }

    public function setCurrentImageLevel(int $currentImageLevel): static
    {
        $this->currentImageLevel = $currentImageLevel;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getGameScore(): ?int
    {
        return $this->gameScore;
    }

    public function setGameScore(int $gameScore): static
    {
        $this->gameScore = $gameScore;

        return $this;
    }
}
