<?php

namespace App\Services\Api\Builders;

use App\Services\Api\Dto\PlanetDiffDto;
use App\Services\Api\Dto\TravelDto;

class TravelBuilder
{
    public static function build($response): TravelDto
    {
        $planetDiffs = [];
        foreach ($response['planetDiffs'] as $planetDiff) {
            $planetDiffs[] = new PlanetDiffDto(
                $planetDiff['from'],
                $planetDiff['to'],
                $planetDiff['fuel']
            );
        }

        return new TravelDto(
            GarbageBuilder::build($response['planetGarbage'] ?? []),
            GarbageBuilder::build($response['shipGarbage'] ?? []),
            $response['fuelDiff'],
            $planetDiffs
        );
    }
}
