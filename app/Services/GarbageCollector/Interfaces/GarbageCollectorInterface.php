<?php

namespace App\Services\GarbageCollector\Interfaces;

interface GarbageCollectorInterface
{
    public function collect(array $shipGarbage, array $planetGarbage): array;
}
