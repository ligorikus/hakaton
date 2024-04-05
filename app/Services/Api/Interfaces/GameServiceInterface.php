<?php

namespace App\Services\Api\Interfaces;

use App\Services\Api\Dto\ResetGameDto;
use App\Services\Api\Dto\UniverseDto;

interface GameServiceInterface
{
    public function rounds(): array;

    public function resetGame(): ResetGameDto;

    public function universe(): UniverseDto;

    public function travel(array $planets);

    public function collect(array $garbage);
}
