<?php

namespace App\Models;

use App\Services\Handler\Dto\GameDto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'round_id',
        'universe_fetched',
    ];

    public $timestamps = false;

    protected static function booted(): void
    {
        static::created(function (Game $game) {
            DB::table('edges')->truncate();
        });
    }

    public function toDto(): GameDto
    {
        return new GameDto(
            $this->id,
            $this->round_id,
            $this->universe_fetched ?? false
        );
    }
}
