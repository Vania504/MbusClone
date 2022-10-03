<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'description',
        'seats',
        'options',
        'status',
    ];

    protected $casts = [
        'options' => AsArrayObject::class,
    ];

    protected $with = ['images'];

    public function images(): HasMany
    {
        return $this->hasMany(BusImage::class, 'bus_id', 'id');
    }
}
