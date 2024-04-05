<?php

namespace App\Services\Api\Dto;

class PlanetDiffDto
{
    public function __construct(
        private string $departure,
        private string $destination,
        private int $cost
    )
    {
    }

    /**
     * @return string
     */
    public function getDeparture(): string
    {
        return $this->departure;
    }

    /**
     * @return string
     */
    public function getDestination(): string
    {
        return $this->destination;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }
}
