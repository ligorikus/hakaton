<?php

namespace App\Services\Api\Interfaces;

use Illuminate\Http\Client\PendingRequest;

interface MethodInterface
{
    public static function handle(PendingRequest $request, array $params = []);
}
