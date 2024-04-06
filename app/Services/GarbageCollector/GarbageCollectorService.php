<?php

namespace App\Services\GarbageCollector;

use App\Models\Garbage;
use App\Services\Api\Dto\GarbageDto;
use App\Services\GarbageCollector\Interfaces\GarbageCollectorInterface;

class GarbageCollectorService implements GarbageCollectorInterface
{
    public const ZMEIKA = [
        [3,3],
        [4,3],
        [4,4],
        [3,4],
        [2,4],
        [2,3],
        [2,2],
        [3,2],
        [4,2],
        [5,2],
        [5,3],
        [5,4],
        [5,5],
        [4,5],
        [3,5],
        [2,5],
        [1,5],
        [1,4],
        [1,3],
        [1,2],
        [1,1],
        [2,1],
        [3,1],
        [4,1],
        [5,1],
        [6,1],
        [6,2],
        [6,3],
        [6,4],
        [6,5],
        [6,6],
        [5,6],
        [4,6],
        [3,6],
        [2,6],
        [1,6],
        [0,6],
        [0,5],
        [0,4],
        [0,3],
        [0,2],
        [0,1],
        [0,0],
        [1,0],
        [2,0],
        [3,0],
        [4,0],
        [5,0],
        [6,0],
        [7,0],
        [7,1],
        [7,2],
        [7,3],
        [7,4],
        [7,5],
        [7,6],
        [7,7],
        [6,7],
        [5,7],
        [4,7],
        [3,7],
        [2,7],
        [1,7],
        [0,7],
        [0,8],
        [1,8],
        [2,8],
        [3,8],
        [4,8],
        [5,8],
        [6,8],
        [7,8],
        [7,9],
        [6,9],
        [5,9],
        [4,9],
        [3,9],
        [2,9],
        [1,9],
        [0,9],
        [0,10],
        [1,10],
        [2,10],
        [3,10],
        [4,10],
        [5,10],
        [6,10],
        [7,10],
    ];


    public function collect(array $shipGarbage, array $planetGarbage): array
    {
        $shipGarbage811 = $this->shipGarbage811($shipGarbage);
        $this->recursiveLoad($planetGarbage, $shipGarbage, $shipGarbage811);
        $this->print811($shipGarbage811);
    }

    public function recursiveLoad(&$planetGarbage, &$shipGarbage, &$shipGarbage811)
    {
        $dirtyResult = $this->dirtyProcessPlanet($shipGarbage, $shipGarbage811, $planetGarbage);
        $shipGarbage = $dirtyResult['shipGarbage'];
        $shipGarbage811 = $dirtyResult['shipGarbage811'];
        $planetGarbage = $dirtyResult['planetGarbage'];

        $emptyZones = $this->caclEmptyZones($shipGarbage811);

        $compactResult = $this->compactProcessPlanet($shipGarbage, $shipGarbage811, $emptyZones);
        $shipGarbage = $compactResult['shipGarbage'];
        $shipGarbage811 = $compactResult['shipGarbage811'];

        $dirtyZag = $this->calcZagruzka($shipGarbage811);

        $planetCompact = $this->planetAfterCompact($planetGarbage, $shipGarbage, $shipGarbage811, $dirtyZag);
        $shipGarbage = $planetCompact['shipGarbage'];
        $shipGarbage811 = $planetCompact['shipGarbage811'];
        $planetGarbage = $planetCompact['planetGarbage'];

        if ($dirtyZag < $planetCompact['zagr']) {
            $this->recursiveLoad($planetGarbage, $shipGarbage, $shipGarbage811);
        }
    }

    public function planetAfterCompact($planetGarbage, $shipGarbage, $shipGarbage811, $zagr)
    {
        $removedFromShip = [];
        /** @var GarbageDto $item */
        foreach ($shipGarbage as $key => $item) {
            $status = false;
            $tempShipGarbage811 = $this->clearShip811FromOneGarbage($shipGarbage811, $item);
            unset($shipGarbage[$key]);

            foreach ($planetGarbage as $garbage) {
                $available = [];
                $i44 = $this->get44($garbage);
                foreach (static::ZMEIKA as $zmK) {
                    $i = $zmK[0];
                    $j = $zmK[1];

                    $collision = $this->checkCollision(
                        $i,
                        $j,
                        $tempShipGarbage811,
                        $i44
                    );
                    if (!$collision) {
                        $available[] = [$i, $j];
                    }
                }

                foreach ($available as $set) {
                    $setResult = $this->setItemToShip($shipGarbage, $shipGarbage811, $set[0], $set[1], $item, $i44);

                    $newZagr = $this->calcZagruzka($setResult['shipGarbage811']);
                    if ($newZagr > $zagr) {
                        $best = $setResult;
                        $zagr = $newZagr;
                        $status = true;
                    }
                }

                if ($status) {
                    $shipGarbage = $best['shipGarbage'];
                    $shipGarbage811 = $best['shipGarbage811'];
                }
            }
            if ($status) {
                $removedFromShip[] = $item;
            } else {
                $shipGarbage[$key] = $item;
            }
        }
        foreach ($removedFromShip as $item) {
            $planetGarbage[] = $item;
        }
        return [
            'planetGarbage' => $planetGarbage,
            'shipGarbage' => $shipGarbage,
            'shipGarbage811' => $shipGarbage811,
            'zagr' => $zagr,
        ];
    }

    public function calcZagruzka($sg811)
    {
        $sum = 0;
        foreach ($sg811 as $row) {
            $sum+=array_sum($row);
        }
        return $sum;
    }

    public function compactProcessPlanet($shipGarbage, $shipGarbage811, $emptyZones)
    {
        /** @var GarbageDto $item */
        foreach ($shipGarbage as $key => $item) {
            $garbage = $this->getByKeyFromDB($item->getKey());
            $available = [];
            $i44 = $this->get44($garbage);

            foreach (static::ZMEIKA as $zmK) {
                $i = $zmK[0];
                $j = $zmK[1];

                $collision = $this->checkCollision(
                    $i,
                    $j,
                    $shipGarbage811,
                    $i44
                );
                if (!$collision) {
                    $available[] = [$i, $j];
                }
            }
            foreach ($available as $set) {
                $setResult = $this->setItemToShip($shipGarbage, $shipGarbage811, $set[0], $set[1], $item, $i44);
                $cleared811 = $this->clearShip811FromOneGarbage($setResult['shipGarbage811'], $item->getItems());

                $clearedEmptyZones = $this->caclEmptyZones($cleared811);
                if ($clearedEmptyZones < $emptyZones) {
                    $emptyZones = $clearedEmptyZones;
                    $shipGarbage = $setResult['shipGarbage'];

                    $shipGarbage811 = $cleared811;
                    unset($shipGarbage[$key]);
                }
            }
        }
        return [
            'shipGarbage' => $shipGarbage,
            'shipGarbage811' => $shipGarbage811
        ];
    }

    public function clearShip811FromOneGarbage($shipGarbage811, $garbage)
    {
        foreach ($garbage as $cell) {
            $shipGarbage811[$cell[0]][$cell[1]] = 0;
        }
        return $shipGarbage811;
    }

    public function getByKeyFromDB(string $key)
    {
        $garbage = Garbage::where('key', $key)->first();
        return new GarbageDto(
            $key,
            json_decode($garbage->data),
        );
    }

    public function caclEmptyZones(array $sg811): int
    {
        $emptyZones = 0;
        for ($i = 0; $i < 8; $i++) {
            for ($j = 0; $j < 11; $j++) {
                if (!$sg811[$i][$j]) {
                    $emptyZones++;
                    $this->markEmptyZone($sg811, $i, $j);
                }
            }
        }
        return $emptyZones;
    }

    public function markEmptyZone(&$sg811, $i, $j)
    {
        if ($i >= 8 || $j >= 11 || $i < 0 || $j < 0 || $sg811[$i][$j]) {
            return;
        }
        $sg811[$i][$j] = 1;
        $this->markEmptyZone($sg811, $i+1, $j);
        $this->markEmptyZone($sg811, $i, $j+1);
        $this->markEmptyZone($sg811, $i, $j-1);
    }

    public function dirtyProcessPlanet(array $shipGarbage, array $shipGarbage811, array $planetGarbage)
    {
        foreach ($planetGarbage as $key => $garbage) {
            $setResult = $this->dirtySet($shipGarbage, $shipGarbage811, $garbage);
            if ($setResult['status']) {
                $shipGarbage = $setResult['shipGarbage'];
                $shipGarbage811 = $setResult['shipGarbage811'];
                unset($planetGarbage[$key]);
            }
        }
        return [
            'shipGarbage' => $shipGarbage,
            'shipGarbage811' => $shipGarbage811,
            'planetGarbage' => $planetGarbage,
        ];
    }

    public function dirtySet(array $shipGarbage, array $shipGarbage811, GarbageDto $item): array
    {
        $available = [];
        $status = false;
        $i44 = $this->get44($item);
        $garbageHaveEmptyZones = $this->garbageHaveEmptyZone($i44);
        foreach (static::ZMEIKA as $zmK) {
            $i = $zmK[0];
            $j = $zmK[1];

            $collision = $this->checkCollision($i, $j, $shipGarbage811, $i44);
            if (!$collision) {
                $available[] = [$i, $j];
            }

        }
        $emptyZones = 100;
        foreach ($available as $set) {
            $setResult = $this->setItemToShip($shipGarbage, $shipGarbage811, $set[0], $set[1], $item, $i44);

            $clearedEmptyZones = $this->caclEmptyZones($setResult['shipGarbage811']);
            if ($clearedEmptyZones < $emptyZones || ($garbageHaveEmptyZones && $clearedEmptyZones < $emptyZones + 1)) {
                $best = $setResult;
                $emptyZones = $clearedEmptyZones;
                $status = true;
            }
        }

        if ($status) {
            $shipGarbage = $best['shipGarbage'];
            $shipGarbage811 = $best['shipGarbage811'];
        }
        return [
            'shipGarbage' => $shipGarbage,
            'shipGarbage811' => $shipGarbage811,
            'status' => $status,
        ];
    }

    private function garbageHaveEmptyZone($i44)
    {
        return $this->caclGarbageEmptyZones($i44) > 0;
    }

    public function caclGarbageEmptyZones(array $i44): int
    {
        $emptyZones = 0;
        for ($i = 0; $i < 4; $i++) {
            for ($j = 0; $j < 4; $j++) {
                if (!$i44[$i][$j]) {
                    $emptyZones++;
                    $this->markGarbageEmptyZone($i44, $i, $j);
                }
            }
        }
        return $emptyZones;
    }

    public function markGarbageEmptyZone(&$i44, $i, $j)
    {
        if ($i >= 4 || $j >= 4 || $i < 0 || $j < 0 || $i44[$i][$j]) {
            return;
        }
        $i44[$i][$j] = 1;
        $this->markGarbageEmptyZone($i44, $i+1, $j);
        $this->markGarbageEmptyZone($i44, $i, $j+1);
        $this->markGarbageEmptyZone($i44, $i, $j-1);
    }

    public function setItemToShip(
        array $shipGarbage,
        array $shipGarbage811,
        int $i,
        int $j,
        GarbageDto $item,
        array $i44
    )
    {
        $resultGarbage = [];
        for ($k = $i; $k < $i+4; $k++) {
            for ($m = $j; $m < $j+4; $m++) {

                if ($k >= 8 || $m >= 11) {
                    continue;
                }
                $current = $i44[$k-$i][$m-$j];
                $shipGarbage811[$k][$m] |= $current;
                if ($current) {
                    $resultGarbage[] = [$k, $m];
                }
            }
        }
        $shipGarbage[] = new GarbageDto($item->getKey(), $resultGarbage);

        return [
            'shipGarbage' => $shipGarbage,
            'shipGarbage811' => $shipGarbage811
        ];
    }

    public function checkCollision($i, $j, $shipGarbage811, $i44): bool
    {
        $collision = false;
        $chunk = [];
        for ($k = 0; $k < 4; $k++) {
            for ($m = 0; $m < 4; $m++) {
                if ($i+$k >= 8) {
                    $chunk[$k][$m] = 1;
                    if ($i44[$k][$m]) {
                        $collision = true;
                    }
                    continue;
                }
                if ($j+$m >= 11) {
                    $chunk[$k][$m] = 1;
                    if ($i44[$k][$m]) {
                        $collision = true;
                    }
                    continue;
                }
                $chunk[$k][$m] = $shipGarbage811[$i+$k][$j+$m];

                if ($i44[$k][$m] && $chunk[$k][$m]) {
                    $collision = true;
                }
            }
        }
        return $collision;
    }

    public function get44(GarbageDto $garbage): array
    {
        $i16 = $garbage->get16();

        $i0 = ($i16 & 0xF000) >> 12;
        $i1 = ($i16 & 0x0F00) >> 8;
        $i2 = ($i16 & 0x00F0) >> 4;
        $i3 = $i16 & 0x000F;

        return [
            [
                (int)(($i0 & 0b1000) > 0),
                (int)(($i0 & 0b0100) > 0),
                (int)(($i0 & 0b0010) > 0),
                (int)(($i0 & 0b0001) > 0)
            ],
            [
                (int)(($i1 & 0b1000) > 0),
                (int)(($i1 & 0b0100) > 0),
                (int)(($i1 & 0b0010) > 0),
                (int)(($i1 & 0b0001) > 0)
            ],
            [
                (int)(($i2 & 0b1000) > 0),
                (int)(($i2 & 0b0100) > 0),
                (int)(($i2 & 0b0010) > 0),
                (int)(($i2 & 0b0001) > 0)
            ],
            [
                (int)(($i3 & 0b1000) > 0),
                (int)(($i3 & 0b0100) > 0),
                (int)(($i3 & 0b0010) > 0),
                (int)(($i3 & 0b0001) > 0)
            ],
        ];
    }

    public function rotate44(array $i44): array
    {
        return [
            [
                $i44[0][3],
                $i44[1][3],
                $i44[2][3],
                $i44[3][3],
            ],
            [
                $i44[0][2],
                $i44[1][2],
                $i44[2][2],
                $i44[3][2],
            ],
            [
                $i44[0][1],
                $i44[1][1],
                $i44[2][1],
                $i44[3][1],
            ],
            [
                $i44[0][0],
                $i44[1][0],
                $i44[2][0],
                $i44[3][0],
            ],
        ];
    }

    /**
     * @param  GarbageDto[]  $shipGarbage
     * @return array
     */
    public function shipGarbage811(array $shipGarbage): array
    {
        $trum = [];
        for ($i = 0; $i < 8; $i++) {
            for ($j = 0; $j < 11; $j++) {
                $trum[$i][$j] = 0;
            }
        }

        foreach ($shipGarbage as $garbage) {
            foreach ($garbage->getItems() as $item) {
                $trum[$item[0]][$item[1]] = 1;
            }
        }

        return $trum;
    }

    private function print811($sg811) {
        foreach ($sg811 as $row) {
            var_dump(implode("", $row));
        }
    }

    private function print44($i44) {
        foreach ($i44 as $row) {
            var_dump(implode("", $row));
        }
    }
}
