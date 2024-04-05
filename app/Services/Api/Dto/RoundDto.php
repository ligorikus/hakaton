<?php

namespace App\Services\Api\Dto;

use Carbon\Carbon;

class RoundDto
{
    public function __construct(
        private string $name,
        private Carbon $startAt,
        private Carbon $endAt,
        private int $planetCount,
        private bool $isCurrent,

        private int $id = 0,
    )
    {
    }

    /**
     * @return Carbon
     */
    public function getEndAt(): Carbon
    {
        return $this->endAt;
    }

    /**
     * @return bool
     */
    public function isCurrent(): bool
    {
        return $this->isCurrent;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPlanetCount(): int
    {
        return $this->planetCount;
    }

    /**
     * @return Carbon
     */
    public function getStartAt(): Carbon
    {
        return $this->startAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
