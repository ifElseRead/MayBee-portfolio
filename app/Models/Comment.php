<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_name',
        'email',
        'content',
    ];

    protected $appends = ['avatar_url'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::get(fn() => 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email ?? ''))) . '?s=100&d=identicon');
    }
}
