<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoundRepository")
 */
class Round
{

    const STATE_PENDING = 0;
    const STATE_IN_PROGRESS = 20;
    const STATE_COMPLETE = 30;

    private $stateLabels = [
        self::STATE_PENDING => 'pending',
        self::STATE_IN_PROGRESS => 'in_progress',
        self::STATE_COMPLETE => 'complete'
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player")
     */
    private $Player1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player")
     */
    private $Player2;

    /**
     * @ORM\Column(type="integer")
     */
    private $Player1Score = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $Player2Score = 0;

    /**
     * @ORM\Column(type="integer", options={"default": "0"})
     */
    private $state = self::STATE_PENDING;


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

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function getStateLabel(): ?string {
        return $this->stateLabels[$this->state];
    }

    public function getAvailableStates(){
        return $this->stateLabels;
    }

    // TODO: Needed for converting the object to JSON; Implement a serializer instead
    public function __toArray(){
        return [
            'player1' => $this->getPlayer1() ? $this->getPlayer1()->getName() : '',
            'player1Score' => $this->getPlayer1Score(),
            'player2' => $this->getPlayer2() ? $this->getPlayer2()->getName() : '',
            'player2Score' => $this->getPlayer2Score()
        ];
    }
}
