<?php

namespace App\Services\Api\Dto;

class RoundDto
{
    public function __construct(
        private string $name,
        private \DateTime $startAt,
        private \DateTime $endAt,
        private int $planetCount,
        private bool $isCurrent,
    )
    {
    }

    /**
     * @return \DateTime
     */
    public function getEndAt(): \DateTime
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
     * @return \DateTime
     */
    public function getStartAt(): \DateTime
    {
        return $this->startAt;
    }
}
