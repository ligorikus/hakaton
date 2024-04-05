<?php

namespace App\Services\Api\Methods;

use App\Services\Api\Interfaces\MethodInterface;
use Illuminate\Http\Client\PendingRequest;

class Travel implements MethodInterface
{
    public static function handle(PendingRequest $request, array $params = [])
    {
        return $request->post('/player/travel', [
            'planets' => $params['planets']
        ])->json();
    }
}
