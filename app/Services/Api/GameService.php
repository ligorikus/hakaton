<?php

namespace App\Services\Api;

use App\Services\Api\Interfaces\GameServiceInterface;
use Illuminate\Http\Client\PendingRequest;

class GameService implements GameServiceInterface
{
    public function __construct(private PendingRequest $request)
    {
    }

    public function test()
    {
        return $this->request->get('')->json();
    }
}
