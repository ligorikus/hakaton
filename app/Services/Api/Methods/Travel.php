<?php

namespace App\Services\Api\Methods;

use App\Services\Api\Builders\TravelBuilder;
use App\Services\Api\Dto\TravelDto;
use App\Services\Api\Interfaces\MethodInterface;
use Illuminate\Http\Client\PendingRequest;

class Travel implements MethodInterface
{
    public static function handle(PendingRequest $request, array $params = []): TravelDto
    {
        $response = $request->post('/player/travel', [
            'planets' => $params['planets']
        ])->json();
        return TravelBuilder::build($response);
    }
}
