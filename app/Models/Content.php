<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'section',
    ];
    protected $with = ['images'];

    public function images(): HasMany
    {
        return $this->hasMany(ContentImage::class, 'content_id', 'id');
    }
}
