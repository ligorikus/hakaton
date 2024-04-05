<?php

namespace App\Services\Api;

use App\Services\Api\Dto\ResetGameDto;
use App\Services\Api\Dto\RoundDto;
use App\Services\Api\Dto\UniverseDto;
use App\Services\Api\Interfaces\GameServiceInterface;
use App\Services\Api\Methods\Collect;
use App\Services\Api\Methods\ResetGame;
use App\Services\Api\Methods\Rounds;
use App\Services\Api\Methods\Travel;
use App\Services\Api\Methods\Universe;
use Illuminate\Http\Client\PendingRequest;

class GameService implements GameServiceInterface
{
    public function __construct(private PendingRequest $request) {}

    /**
     * @return RoundDto[]
     */
    public function rounds(): array
    {
        return Rounds::handle($this->request);
    }

    /**
     * @return ResetGameDto
     */
    public function resetGame(): ResetGameDto
    {
        return ResetGame::handle($this->request);
    }

    public function universe(): UniverseDto
    {
        return Universe::handle($this->request);
    }

    public function travel(array $planets)
    {
        return Travel::handle($this->request, ['planets' => $planets]);
    }

    public function collect(array $garbage)
    {
        return Collect::handle($this->request, ['garbage' => $garbage]);
    }
}
