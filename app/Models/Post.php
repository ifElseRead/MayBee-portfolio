<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; // 👈 Added for the accessor
use Illuminate\Support\Str;                        // 👈 Added for Str::markdown

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'body',
        'banner_image',
        'prompt_topic',
        'status',
        'created_at',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // This automatically appends 'html_body' whenever this model is sent to Inertia
    protected $appends = ['html_body'];

    /**
     * Automatically convert the markdown 'body' to HTML.
     */
    protected function htmlBody(): Attribute
    {
        return Attribute::get(fn() => Str::markdown($this->body ?? ''));
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
