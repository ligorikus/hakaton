<?php

namespace App\Services\Api\Dto;

class EdgeDto
{
    private string $departure;
    private string $destination;
    private int $cost;

    public function __construct(
        array $edge
    )
    {
        $this->departure = $edge[0];
        $this->destination = $edge[1];
        $this->cost = $edge[2];
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
