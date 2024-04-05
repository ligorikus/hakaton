<?php

namespace App\Services\Api\Methods;

use App\Services\Api\Dto\ResetGameDto;
use App\Services\Api\Interfaces\MethodInterface;
use Illuminate\Http\Client\PendingRequest;

class ResetGame implements MethodInterface
{
    public static function handle(PendingRequest $request, array $params = []): ResetGameDto
    {
        $response = $request->delete('/player/reset')->json();
        return new ResetGameDto($response['success']);
    }
}
