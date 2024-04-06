<?php

namespace App\Services\GarbageCollector\Interfaces;

interface GarbageCollectorInterface
{
    public function collect(array $shipGarbage, array $planetGarbage): array;

    public function calcZagruzka($sg811): int;

    public function shipGarbage811(array $shipGarbage): array;
}
