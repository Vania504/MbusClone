<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'message',
        'status',
        'phone_number',
    ];
    protected $with = ['status'];

    public function status(): HasOne
    {
        return $this->hasOne(Status::class, 'id', 'status');
    }
}
