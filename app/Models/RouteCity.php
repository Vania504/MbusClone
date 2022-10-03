<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'name',
        'lat',
        'lng',
        'type',
    ];
}
