<?php

namespace App\Models;

use App\Services\Api\Dto\RoundDto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_at',
        'end_at',
        'planet_count',
        'is_current',
    ];

    public $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime'
    ];

    public $timestamps = false;

    public function toDto(): RoundDto
    {
        return new RoundDto(
            $this->name,
            $this->start_at,
            $this->end_at,
            $this->planet_count,
            $this->is_current,

            $this->id
        );

    }
}
