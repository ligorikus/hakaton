<?php

namespace App\Services\Api\Dto;

class PlanetDto
{
    /**
     * @param  string  $name
     * @param  GarbageDto[]  $garbage
     */
    public function __construct(
        private string $name,
        private array $garbage
    )
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getGarbage(): array
    {
        return $this->garbage;
    }
}
