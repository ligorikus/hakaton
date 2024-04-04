<?php

namespace App\Services\Api;

use Illuminate\Http\Client\PendingRequest;

class ApiService
{
    public function __construct(private PendingRequest $request)
    {
    }
}
