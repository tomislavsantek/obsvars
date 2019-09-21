<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoundRepository")
 */
class Round
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="rounds")
     */
    private $Player1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="rounds")
     */
    private $Player2;

    /**
     * @ORM\Column(type="integer")
     */
    private $Player1Score;

    /**
     * @ORM\Column(type="integer")
     */
    private $Player2Score;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer1(): ?Player
    {
        return $this->Player1;
    }

    public function setPlayer1(?Player $Player1): self
    {
        $this->Player1 = $Player1;

        return $this;
    }

    public function getPlayer2(): ?Player
    {
        return $this->Player2;
    }

    public function setPlayer2(?Player $Player2): self
    {
        $this->Player2 = $Player2;

        return $this;
    }

    public function getPlayer1Score(): ?int
    {
        return $this->Player1Score;
    }

    public function setPlayer1Score(int $Player1Score): self
    {
        $this->Player1Score = $Player1Score;

        return $this;
    }

    public function getPlayer2Score(): ?int
    {
        return $this->Player2Score;
    }

    public function setPlayer2Score(int $Player2Score): self
    {
        $this->Player2Score = $Player2Score;

        return $this;
    }
}
