<?php

namespace App\Services\Api\Dto;

class GarbageDto
{
    public function __construct(
        private string $key,
        private array $items,
    )
    {
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}
