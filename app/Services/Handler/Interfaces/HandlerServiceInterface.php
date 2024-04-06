<?php

namespace App\Services\Handler\Interfaces;

use App\Services\Api\Dto\RoundDto;
use App\Services\Handler\Dto\GameDto;

interface HandlerServiceInterface
{
    public function getCurrentRound(): ?RoundDto;

    public function getCurrentGame(): GameDto;

    public function fetchUniverse(GameDto $game): bool;

    public function pathfinder($from, $to): array;

    public function getNear($currrent);

    public function moveToEden($current);

    public function shipGarbagePerc($shipGarbage);
}
