<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ContentImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_id',
        'image_id',
    ];
    protected $with = ['images'];

    public function images(): HasOne
    {
        return $this->hasOne(Image::class, 'id', 'image_id');
    }
}
