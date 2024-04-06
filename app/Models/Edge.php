<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edge extends Model
{
    use HasFactory;

    protected $fillable = [
        'departure',
        'destination',
        'cost'
    ];

    public $timestamps = false;

    public function planets()
    {
        return $this->hasMany(Planet::class, 'name', 'destination');
    }
}
