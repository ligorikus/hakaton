<?php

namespace App\Services\Api\Builders;

use App\Services\Api\Dto\EdgeDto;
use App\Services\Api\Dto\PlanetDto;
use App\Services\Api\Dto\ShipDto;
use App\Services\Api\Dto\UniverseDto;

class UniverseBuilder
{
    public static function build(array $response): UniverseDto
    {

        $name = $response['name'] ?? '';
        $edges = static::buildEdges($response['universe']);
        $ship = static::buildShip($response['ship']);

        $roundName = $response['roundName'];
        $roundEndIn = $response['roundEndIn'];
        $attempt = $response['attempt'];

        return new UniverseDto(
            $name,
            $ship,
            $edges,
            $roundName,
            $roundEndIn,
            $attempt
        );
    }

    /**
     * @param array $edges
     * @return EdgeDto[]
     */
    private static function buildEdges(array $edges): array
    {
        $result = [];
        foreach ($edges as $edge) {
            $result[] = new EdgeDto($edge);
        }
        return $result;
    }

    /**
     * @param  array  $ship
     * @return ShipDto
     */
    private static function buildShip(array $ship): ShipDto
    {
        $planet = new PlanetDto(
            $ship['planet']['name'],
            GarbageBuilder::build($ship['planet']['garbage'])
        );

        return new ShipDto(
            $ship['capacityX'],
            $ship['capacityY'],
            $ship['fuelUsed'],
            GarbageBuilder::build($ship['garbage']),
            $planet
        );
    }
}
