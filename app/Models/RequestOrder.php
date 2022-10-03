<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RequestOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone_number',
        'count',
        'departure',
        'bus_id',
        'destination',
        'route_id',
        'status',
    ];
    protected $with = ['status','bus'];

    public function status(): HasOne
    {
        return $this->hasOne(Status::class, 'id', 'status');
    }
    public function bus(): HasOne
    {
        return $this->hasOne(Bus::class, 'id', 'bus_id');
    }
}
