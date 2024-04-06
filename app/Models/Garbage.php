<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Garbage extends Model
{
    use HasFactory;

    protected $casts = [
        'data' => 'json'
    ];

    protected $fillable = [
        'key',
        'data'
    ];

    public $timestamps = false;
}
