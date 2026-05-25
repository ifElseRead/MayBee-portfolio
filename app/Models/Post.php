<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'body',
        'banner_image',
        'is_published',
    ];
}
