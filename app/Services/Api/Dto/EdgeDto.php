<?php

namespace App\Services\Api\Dto;

class EdgeDto
{
    private string $vertex1;
    private string $vertex2;
    private int $cost;

    public function __construct(
        array $edge
    )
    {
        $this->vertex1 = $edge[0];
        $this->vertex2 = $edge[1];
        $this->cost = $edge[2];
    }

    /**
     * @return string
     */
    public function getVertex1(): string
    {
        return $this->vertex1;
    }

    /**
     * @return string
     */
    public function getVertex2(): string
    {
        return $this->vertex2;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }
}
