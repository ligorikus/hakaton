<?php

namespace App\Services\Handler\Dto;

use App\Models\Game;

class GameDto
{
    public function __construct(
        private int $id,
        private int $roundId,
        private bool $universeFetched = false
    )
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getRoundId(): int
    {
        return $this->roundId;
    }

    /**
     * @return bool
     */
    public function isUniverseFetched(): bool
    {
        return $this->universeFetched;
    }

    public function setUniverseFetched(bool $universeFetched): void
    {
        $this->universeFetched = $universeFetched;
    }

    public function toDB(): void
    {
        $game = Game::find($this->getId());
        $game->round_id = $this->getRoundId();
        $game->universe_fetched = $this->isUniverseFetched();
        $game->save();
    }
}
