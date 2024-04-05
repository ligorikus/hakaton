<?php

namespace App\Services\Api\Dto;

class ShipDto
{
    public function __construct(
        private int $capacityX,
        private int $capacityY,
        private int $fuelUsed,
        private array $garbage,
        private PlanetDto $planet,
    )
    {
    }

    /**
     * @return int
     */
    public function getCapacityX(): int
    {
        return $this->capacityX;
    }

    /**
     * @return int
     */
    public function getCapacityY(): int
    {
        return $this->capacityY;
    }

    /**
     * @return int
     */
    public function getFuelUsed(): int
    {
        return $this->fuelUsed;
    }

    /**
     * @return GarbageDto[]
     */
    public function getGarbage(): array
    {
        return $this->garbage;
    }

    /**
     * @return PlanetDto
     */
    public function getPlanet(): PlanetDto
    {
        return $this->planet;
    }
}
