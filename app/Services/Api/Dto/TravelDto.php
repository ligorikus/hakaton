<?php

namespace App\Services\Api\Dto;

class TravelDto
{
    public function __construct(
        private array $planetGarbage,
        private array $shipGarbage,
        private int $fuelDiff,
        private array $planetDiffs
    )
    {
    }

    /**
     * @return GarbageDto[]
     */
    public function getPlanetGarbage(): array
    {
        return $this->planetGarbage;
    }

    /**
     * @return GarbageDto[]
     */
    public function getShipGarbage(): array
    {
        return $this->shipGarbage;
    }

    /**
     * @return int
     */
    public function getFuelDiff(): int
    {
        return $this->fuelDiff;
    }

    /**
     * @return PlanetDiffDto[]
     */
    public function getPlanetDiffs(): array
    {
        return $this->planetDiffs;
    }
}
