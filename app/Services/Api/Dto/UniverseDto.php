<?php

namespace App\Services\Api\Dto;

class UniverseDto
{
    public function __construct(
        private string $name,
        private ShipDto $ship,
        private array $edges,

        private string $roundName = '',
        private int $roundEndIn = 0,
        private int $attempt = 1,
    )
    {
    }

    /**
     * @return string
     */
    public function getRoundName(): string
    {
        return $this->roundName;
    }

    /**
     * @return int
     */
    public function getAttempt(): int
    {
        return $this->attempt;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return ShipDto
     */
    public function getShip(): ShipDto
    {
        return $this->ship;
    }

    /**
     * @return EdgeDto[]
     */
    public function getEdges(): array
    {
        return $this->edges;
    }

    /**
     * @return int
     */
    public function getRoundEndIn(): int
    {
        return $this->roundEndIn;
    }
}
