<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'departure',
        'destination',
        'driver_phones',
        'departure_days',
        'bus_id',
        'departure_time',
        'route_path_image_id',
        'route_time',
        'status',
    ];

    protected $with = ['cities', 'bus','images'];

    protected $casts = [
        'driver_phones' => AsArrayObject::class,
        'route_time' => AsArrayObject::class,
        'departure_days' => AsCollection::class,
    ];

    public function images(): HasMany
    {
        return $this->hasMany(RouteImage::class, 'route_id', 'id');
    }

    public function cities(): HasMany
    {
        return $this->hasMany(RouteCity::class, 'route_id', 'id');
    }

    public function bus(): HasOne
    {
        return $this->hasOne(Bus::class, 'id', 'bus_id');
    }

}

