<?php

namespace App\Services\Api\Dto;

class ResetGameDto
{
    public function __construct(private bool $success)
    {
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }
}
