<?php

namespace App\Services\Api\Builders;

use App\Services\Api\Dto\GarbageDto;

class GarbageBuilder
{
    public static function build($garbage): array
    {
        $result = [];
        foreach ($garbage as $key => $value) {
            $item = new GarbageDto(
                $key,
                $value
            );
            $item->toDB();
            $result[] = $item;
            $item->toDB();
        }
        return $result;
    }
}
