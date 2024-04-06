<?php

namespace App\Services\Api\Dto;

use App\Models\Garbage;

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

    public function get16(): int
    {
        $i = 0x00;
        foreach ($this->getItems() as $item) {
            $mask = 2 ** (3 - $item[1]);
            $mask <<= 3*4 - $item[0]*4;
            $i |= $mask;
        }
        return $i;
    }

    public function toDB(): void
    {
        Garbage::where('key', $this->getKey())->firstOrCreate([
            'key' => $this->getKey(),
            'data' => json_encode($this->getItems())
        ]);
    }
}
