<?php

namespace App\Services\Api\Methods;

use App\Services\Api\Dto\RoundDto;
use App\Services\Api\Interfaces\MethodInterface;
use Illuminate\Http\Client\PendingRequest;

class Rounds implements MethodInterface
{
    /**
     * @param  PendingRequest  $request
     * @return RoundDto[]
     */
    public static function handle(PendingRequest $request, array $params = []): array
    {
        $response = $request->get('/player/rounds')->json();
        return static::process($response['rounds']);
    }

    /**
     * @param  array  $response
     * @return RoundDto[]
     */
    private static function process(array $response): array
    {
        $rounds = [];
        foreach ($response as $round) {
            $rounds[] = new RoundDto(
                $round['name'],
                \DateTime::createFromFormat("Y-m-d H:i:s", $round['startAt']),
                \DateTime::createFromFormat("Y-m-d H:i:s", $round['endAt']),
                $round['planetCount'],
                $round['isCurrent'],
            );
        }
        return $rounds;
    }
}
