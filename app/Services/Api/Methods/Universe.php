<?php

namespace App\Services\Api\Methods;

use App\Services\Api\Builders\UniverseBuilder;
use App\Services\Api\Dto\UniverseDto;
use App\Services\Api\Interfaces\MethodInterface;
use Illuminate\Http\Client\PendingRequest;

class Universe implements MethodInterface
{
    public static function handle(PendingRequest $request, array $params = []): UniverseDto
    {
        $response = $request->get('/player/universe')->json();
        return UniverseBuilder::build($response);
    }
}
